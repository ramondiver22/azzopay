<?php

namespace App\Lib\BancoOriginal;

class Banco_original_pix extends BancoOriginalRequest {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Criar cobrança imediata.
     * @param Integer $expiracao Tempo de vida da cobrança, especificado em segundos a partir da data de criação (Calendario.criacao)
     * @param Double $amount
     * @param String $pixkey  Chave DICT do recebedor
     * @param Boolean $acceptChange Trata-se de um campo que determina se o valor final do documento pode ser alterado pelo pagador. Na ausência desse campo, assume-se que não se pode alterar o valor do documento de cobrança, ou seja, assume-se o valor 0. Se o campo estiver presente e com valor 1, então está determinado que o valor final da cobrança pode ter seu valor alterado pelo pagador.
     * @param String $document
     * @param String $name
     * @param String $solicitacaoPagador O campo solicitacaoPagador, opcional, determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor. Esse texto será preenchido, na pacs.008, pelo PSP do pagador, no campo RemittanceInformation . O tamanho do campo na pacs.008 está limitado a 140 caracteres.
     * @param Array $infos No formato Array("nomeDoCampo" => "valor do campo", "nomeDoCampo" => "valor do campo")
     * @return Object
     */
    public function criarCobranca($expiracao, $amount, $pixkey, $acceptChange = false, $document = null, $name = null, $solicitacaoPagador = null, $infos = null) {
        
        $path = "pix-receipts/v1/cob";
        $method = "POST";
        $body = $this->cobBody($expiracao, $amount, $pixkey, $acceptChange, $document, $name, $solicitacaoPagador, $infos);
        
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     *  Revisar cobrança imediata.
     * @param String $txid
     * @param Double $amount
     * @param String $document
     * @param String $name
     * @param Boolean $solicitacaoPagador O campo solicitacaoPagador, opcional, determina um texto a ser apresentado ao pagador para que ele possa digitar uma informação correlata, em formato livre, a ser enviada ao recebedor. Esse texto será preenchido, na pacs.008, pelo PSP do pagador, no campo RemittanceInformation . O tamanho do campo na pacs.008 está limitado a 140 caracteres.
     * @param Integer $loc Identificador da localização do payload.
     * @return Object
     * @throws \Exception
     */
    public function alterarCobranca($txid, $amount, $document = null, $name = null, $solicitacaoPagador = null, $loc = null) {
        
        $path = "pix-receipts/v1/cob/{$txid}";
        $method = "PATCH";
        $body = Array();
        
        if ($amount != null) {
            $body["valor"] = Array(
                "original" => number_format($amount, 2, ".", "")
            );
        }
        
        if ($loc != null) {
            $body["loc"] = Array(
                "id" => $loc
            );
        }
        
        if (!empty($document) && !empty($name)) {
            $document = str_replace(Array(".", "/", "-"), "", $document);
            $documentKey = (strlen($document) > 11 ? "cnpj" : "cpf");
            
            $devedor = Array(
                "{$documentKey}" => $document,
                "nome" => $name
            );
            
            $body["devedor"] = $devedor;
        }
        
        if (!empty($solicitacaoPagador)) {
            $body["solicitacaoPagador"] = $solicitacaoPagador;
        }
        
        if (sizeof($body) > 0){
            throw new \Exception("É necessário informar os dados a serem alterados.");
        }
        
        return $this->makeRequest($method, $path, $body);
    }
    
    private function cobBody($expiracao, $amount, $pixkey, $acceptChange = false, $document = null, $name = null, $solicitacaoPagador = null, $infos = null) {
        $body = Array(
            "calendario" => Array (
                "expiracao" => $expiracao
            ),
            "valor" => Array(
              "original" => number_format($amount, 2, ".", ""),
              "modalidadeAlteracao" => ($acceptChange ? 1 : 0)
            ),
            "chave" => $pixkey
        );
        
        
        
        if (!empty($document) && !empty($name)) {
            $document = str_replace(Array(".", "/", "-"), "", $document);
            $documentKey = (strlen($document) > 11 ? "cnpj" : "cpf");
            
            $devedor = Array(
                "{$documentKey}" => $document,
                "nome" => $name
            );
            
            $body["devedor"] = $devedor;
        }
        
        if (!empty($solicitacaoPagador)) {
            $body["solicitacaoPagador"] = $solicitacaoPagador;
        }
        
        if (sizeof($infos) > 0) {
            
            $body["infoAdicionais"] = Array();
            
            foreach ($infos as $name => $value) {
                $body["infoAdicionais"][] = Array(
                    "nome" => $name,
                    "valor" => $value
                );
            }
        }
        
        return $body;
    }
    
    
    /**
     * Endpoint para consultar uma cobrança através de um determinado txid.
     * @param String $txid
     * @param Integer $revisao
     * @return Object
     */
    public function consultarCobranca( $txid, $revisao) {
        
        
        $path = "pix-receipts/v1/cob/{$txid}";
        $method = "GET";
        $body = Array();
        
        if (!is_null($revisao)) {
            $body["revisao"] = $revisao;
        }
        
        return $this->makeRequest($method, $path, $body);
        
    }
    
    
    /**
     * Endpoint para consultar cobranças imediatas através de parâmetros como início, fim, cpf, cnpj e status.
     * @param String $txid
     * @param String $inicio Date time no formato yyyy-mm-dd hh:mm:ss
     * @param String $fim Date time no formato yyyy-mm-dd hh:mm:ss
     * @param String $cpf
     * @param String $cnpj
     * @param Boolean $locationPresente
     * @param String $status  Valores válidos: ATIVA, CONCLUIDA, REMOVIDA_PELO_USUARIO_RECEBEDOR, REMOVIDA_PELO_PSP, ATIVA, CONCLUIDA, REMOVIDA_PELO_USUARIO_RECEBEDOR, REMOVIDA_PELO_PSP
     * @param Integer $paginaAtual
     * @param Integer $itensPorPagina
     * @return Array
     */
    public function listarCobrancas( $txid, $inicio, $fim, $cpf = null, $cnpj = null, $locationPresente = null, $status =  null, $paginaAtual = 0, $itensPorPagina = 100) {
        
        
        $path = "pix-receipts/v1/cob/{$txid}";
        $method = "GET";
        $body = Array(
            "inicio" => $inicio,
            "fim" => $fim
        );
        
        if (!empty($cpf)) {
            $body["cpf"] = $cpf;
        }
        
        if (!empty($cnpj)) {
            $body["cnpj"] = $cnpj;
        }
        
        if (is_bool($locationPresente)) {
            $body["locationPresente"] = ($locationPresente ? 'true' : 'false');
        }
        
        if (!in_array(strtoupper($status), Array("ATIVA", "CONCLUIDA", "REMOVIDA_PELO_USUARIO_RECEBEDOR", "REMOVIDA_PELO_PSP", "ATIVA", "CONCLUIDA", "REMOVIDA_PELO_USUARIO_RECEBEDOR", "REMOVIDA_PELO_PSP"))) {
            $body["status"] = strtoupper($status);
        }
        
        if (is_numeric($paginaAtual)) {
            $body["paginacao.paginaAtual"] = $paginaAtual;
        }
        
        if (is_numeric($itensPorPagina)) {
            $body["paginacao.paginaAtual"] = $itensPorPagina;
        }
        return $this->makeRequest($method, $path, $body);
        
    }
    
    /**
     * Criar location do payload
     * @param String $tipo valores válidos: cob, cobv
     * @return Object
     * @throws \Exception
     */
    public function createLocation($tipo) {
        
        if (!in_array($tipo, Array("cob", "cobv"))) {
            throw new \Exception("Tipo de location inválida.");
        }
        
        $path = "pix-receipts/v1/loc";
        $method = "POST";
        $body = Array(
             "tipoCob" => "cob"
        );
        
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     * Recupera a location do payload
     * @param String $id
     * @return Object
     */
    public function getLocation($id) {
        
        $path = "pix-receipts/v1/loc/{$id}";
        $method = "GET";
        $body = Array(
             
        );
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     * Endpoint para consultar locations cadastradas
     * @param String $inicio Date time no formato yyyy-mm-dd hh:mm:ss
     * @param String $fim Date time no formato yyyy-mm-dd hh:mm:ss
     * @param Boolean $txIdPresente 
     * @param String $tipoCob valores válidos: cob, cobv
     * @param Integer $paginaAtual
     * @param Integer $itensPorPagina
     * @return Array
     */
    public function listarLocations($inicio, $fim, $txIdPresente = null, $tipoCob = null, $paginaAtual = 0, $itensPorPagina = 100) {
        
        $path = "pix-receipts/v1/loc";
        $method = "GET";
        $body = Array(
            "inicio" => $inicio,
            "fim" => $fim
        );
        
        if (is_bool($txIdPresente)) {
            $body["txIdPresente"] = ($txIdPresente ? 'true' : 'false');
        }
        
        if (in_array($tipoCob, Array("cob", "cobv"))) {
            $body["tipoCob"] = $tipoCob;
        }
        
        if (is_numeric($paginaAtual)) {
            $body["paginacao.paginaAtual"] = $paginaAtual;
        }
        
        if (is_numeric($itensPorPagina)) {
            $body["paginacao.paginaAtual"] = $itensPorPagina;
        }
        
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     * Endpoint utilizado para desvincular uma cobrança de uma location.
     * <br><br>
     * Se executado com sucesso, a entidade loc não apresentará mais um txid, se apresentava anteriormente à chamada. Adicionalmente, a entidade cob ou cobv associada ao txid desvinculado também passará a não mais apresentar um location. Esta operação não altera o status da cob ou cobv em questão.
     * <br><br>
     * @param String $id
     * @return Object
     */
    public function desvincularTxid($id) {
        
        $path = "pix-receipts/v1/loc/{$id}/txid";
        $method = "DELETE";
        $body = Array(
             
        );
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     * Endpoint para consultar um Pix através de um e2eid.
     * @param String $e2eId
     * @return Object
     */
    public function consultarPixRecebido($e2eId) {
        
        $path = "pix-receipts/v1/pix/{$e2eId}";
        $method = "GET";
        $body = Array(
             
        );
        return $this->makeRequest($method, $path, $body);
    } 
    
    
    /**
     * Endpoint para consultar Pix recebidos
     * @param String $inicio Date time no formato yyyy-mm-dd hh:mm:ss
     * @param String $fim Date time no formato yyyy-mm-dd hh:mm:ss
     * @param String $txid
     * @param Boolean $txIdPresente
     * @param Boolean $devolucaoPresente
     * @param String $cpf
     * @param String $cnpj
     * @param Integer $paginaAtual
     * @param Integer $itensPorPagina
     * @return Array
     */
    public function listarPixRecebidos($inicio, $fim, $txid = null, $txIdPresente = null, $devolucaoPresente = null, $cpf= null, $cnpj = null, $paginaAtual = 0, $itensPorPagina = 100) {
        
        $path = "pix-receipts/v1/pix";
        $method = "GET";
        $body = Array(
            "inicio" => $inicio,
            "fim" => $fim
        );
        
        if (!empty($txid)) {
            $body["txid"] = $txid;
        }
        
        if (!empty($cpf)) {
            $body["cpf"] = $cpf;
        }
        
        if (!empty($cnpj)) {
            $body["cnpj"] = $cnpj;
        }
        
        
        if (is_bool($txIdPresente)) {
            $body["txIdPresente"] = ($txIdPresente ? 'true' : 'false');
        }
        
        
        if (is_bool($devolucaoPresente)) {
            $body["devolucaoPresente"] = ($devolucaoPresente ? 'true' : 'false');
        }
        
        
        if (is_numeric($paginaAtual)) {
            $body["paginacao.paginaAtual"] = $paginaAtual;
        }
        
        if (is_numeric($itensPorPagina)) {
            $body["paginacao.paginaAtual"] = $itensPorPagina;
        }
        
        return $this->makeRequest($method, $path, $body);
    }
    
    /**
     * Endpoint para solicitar uma devolução através de um e2eid do Pix e do ID da devolução. O motivo que será atribuído à PACS.004 será "Devolução solicitada pelo usuário recebedor do pagamento original" cuja sigla é "MD06" de acordo com a aba RTReason da PACS.004 que consta no Catálogo de Mensagens do Pix.
     * @param String $e2eId
     * @param String $id
     * @param Doule $value
     * @return Object
     */
    public function solicitarDevolucao($e2eId, $id, $value) {
        
        $path = "pix-receipts/v1/pix/{$e2eId}/devolucao/{$id}";
        $method = "PUT";
        $body = Array(
             "value" => number_format($value, 2, ".", "")
        );
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     * Endpoint para consultar uma devolução através de um End To End ID do Pix e do ID da devolução
     * @param String $e2eId
     * @param String $id
     * @return Object
     */
    public function consultarDevolucao($e2eId, $id) {
        
        $path = "pix-receipts/v1/pix/{$e2eId}/devolucao/{$id}";
        $method = "GET";
        $body = Array(
             
        );
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     * Endpoint para configuração do serviço de notificações acerca de Pix recebidos. Somente Pix associados a um txid serão notificados.
     * @param String $chave
     * @param String $txid
     * @return Object
     */
    public function configurarWebhook($chave, $txid) {
        
        $path = "pix-receipts/v1/webhook/{$chave}";
        $method = "PUT";
        $body = Array(
             "webhookUrl" => base_url("cron/banco_original/webhook/{$txid}")
        );
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     * Endpoint para recuperação de informações sobre o Webhook Pix.
     * @param String $chave
     * @return Object
     */
    public function consultarWebhook($chave) {
        
        $path = "pix-receipts/v1/webhook/{$chave}";
        $method = "GET";
        $body = Array(
             
        );
        return $this->makeRequest($method, $path, $body);
    }
    
    
    /**
     * Endpoint para cancelamento do webhook. Não é a única forma pela qual um webhook pode ser removido.
     * <br><br>
     * O PSP recebedor está livre para remover unilateralmente um webhook que esteja associado a uma chave que não pertence mais a este usuário recebedor.
     * <br><br>
     * @param String $chave
     * @return Object
     */
    public function cancelarWebhook($chave) {
        
        $path = "pix-receipts/v1/webhook/{$chave}";
        $method = "DELETE";
        $body = Array(
             
        );
        return $this->makeRequest($method, $path, $body);
    }
    
    /**
     * Endpoint para consultar Webhooks cadastrados
     * @param String $inicio Date time no formato yyyy-mm-dd hh:mm:ss
     * @param String $fim Date time no formato yyyy-mm-dd hh:mm:ss
     * @param Integer $paginaAtual
     * @param Integer $itensPorPagina
     * @return Array
     */
    public function listarWebhooks($inicio, $fim, $paginaAtual = 0, $itensPorPagina = 100) {
        
        $path = "pix-receipts/v1/webhook";
        $method = "GET";
        $body = Array(
            "inicio" => $inicio,
            "fim" => $fim
        );
        
        
        if (is_numeric($paginaAtual)) {
            $body["paginacao.paginaAtual"] = $paginaAtual;
        }
        
        if (is_numeric($itensPorPagina)) {
            $body["paginacao.paginaAtual"] = $itensPorPagina;
        }
        
        return $this->makeRequest($method, $path, $body);
    }
    
    /**
     * Recuperar o payload JSON que representa a cobrança imediata.
     * @param String $pixUrlAccessToken
     * <br><br>
     * No momento que o usuário devedor efetua a leitura de um QR Code dinâmico gerado pelo recebedor, esta URL será acessada e seu conteúdo consiste em uma estrutura JWS. 
     * <br><br>
     * @return Object
     */
    public function cobPayload($pixUrlAccessToken) {
        
        $path = "pix-receipts/v1/{$pixUrlAccessToken}";
        $method = "GET";
        $body = Array(
             
        );
        return $this->makeRequest($method, $path, $body);
    }
}