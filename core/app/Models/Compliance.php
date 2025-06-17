<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Builder;
use Validator;

class Compliance extends Model {
    protected $table = "compliance";
    protected $guarded = [];

    const STATUS_PERSONAL = Array(
        'PENDING' => Array("value" => "PENDING", "label" =>  "warning", "text" => "Aguardando Análise"),
        'ANALYSIS' => Array("value" => "ANALYSIS", "label" =>  "primary", "text" => "Em Análise"),
        'REJECTED' => Array("value" => "REJECTED", "label" =>  "danger", "text" => "Rejeitado"),
        'APPROVED' => Array("value" => "APPROVED", "label" =>  "success", "text" => "Aprovado"),
        'NONE' => Array("value" => "NONE", "label" =>  "secondary", "text" => "Não Informado")
    );

    const STATUS_BUSINESS = Array(
        'PENDING' => Array("value" => "PENDING", "label" =>  "warning", "text" => "Aguardando Análise"),
        'ANALYSIS' => Array("value" => "ANALYSIS", "label" =>  "primary", "text" => "Em Análise"),
        'REJECTED' => Array("value" => "REJECTED", "label" =>  "danger", "text" => "Rejeitado"),
        'APPROVED' => Array("value" => "APPROVED", "label" =>  "success", "text" => "Aprovado"),
        'NONE' => Array("value" => "NONE", "label" =>  "secondary", "text" => "Não Informado")
    );


    const DOCUMENT_STATUS = Array(
        'PENDING' => Array("value" => "PENDING", "label" =>  "warning", "text" => "Aguardando Análise", "image" => "hourglass-orange.png"),
        'REJECTED' => Array("value" => "REJECTED", "label" =>  "danger", "text" => "Rejeitado", "image" => "cross-red.png"),
        'APPROVED' => Array("value" => "APPROVED", "label" =>  "success", "text" => "Aprovado", "image" => "mark-green.png"),
        'NONE' => Array("value" => "NONE", "label" =>  "secondary", "text" => "Não Informado", "image" => "cross-red.png")
    );
    const PROOF_STATUS = Array(
        'PENDING' => Array("value" => "PENDING", "label" =>  "warning", "text" => "Aguardando Análise", "image" => "hourglass-orange.png"),
        'REJECTED' => Array("value" => "REJECTED", "label" =>  "danger", "text" => "Rejeitado", "image" => "cross-red.png"),
        'APPROVED' => Array("value" => "APPROVED", "label" =>  "success", "text" => "Aprovado", "image" => "mark-green.png"),
        'NONE' => Array("value" => "NONE", "label" =>  "secondary", "text" => "Não Informado", "image" => "cross-red.png")
    );
    const SELFIE_STATUS = Array(
        'PENDING' => Array("value" => "PENDING", "label" =>  "warning", "text" => "Aguardando Análise", "image" => "hourglass-orange.png"),
        'REJECTED' => Array("value" => "REJECTED", "label" =>  "danger", "text" => "Rejeitado", "image" => "cross-red.png"),
        'APPROVED' => Array("value" => "APPROVED", "label" =>  "success", "text" => "Aprovado", "image" => "mark-green.png"),
        'NONE' => Array("value" => "NONE", "label" =>  "secondary", "text" => "Não Informado", "image" => "cross-red.png")
    );
    
    const REGISTRY_STATUS = Array(
        'PENDING' => Array("value" => "PENDING", "label" =>  "warning", "text" => "Aguardando Análise", "image" => "hourglass-orange.png"),
        'REJECTED' => Array("value" => "REJECTED", "label" =>  "danger", "text" => "Rejeitado", "image" => "cross-red.png"),
        'APPROVED' => Array("value" => "APPROVED", "label" =>  "success", "text" => "Aprovado", "image" => "mark-green.png"),
        'NONE' => Array("value" => "NONE", "label" =>  "secondary", "text" => "Não Informado", "image" => "cross-red.png")
    );

    const INDUSTRIAL_ACTIVITY = Array(
        "Agriculture" => Array("value" => "Agriculture", "label" => "info", "text" => "Agricultura"),
        "Commerce" => Array("value" => "Commerce", "label" => "info", "text" => "Comércio"),
        "Education" => Array("value" => "Education", "label" => "info", "text" => "Educação"),
        "Financial Services" => Array("value" => "Financial Services", "label" => "info", "text" => "Serviços Financeiros"),
        "Gaming" => Array("value" => "Gaming", "label" => "info", "text" => "Jogos"),
        "Hospitality" => Array("value" => "Hospitality", "label" => "info", "text" => "Hotel/Pousada"),
        "Health" => Array("value" => "Health", "label" => "info", "text" => "Saúde"),
        "Leisure &amp; Entertainment" => Array("value" => "Leisure &amp; Entertainment", "label" => "info", "text" => "Lazer e Entretenimento"),
        "Utilities" => Array("value" => "Utilities", "label" => "info", "text" => "Utilidades"),
        "Logistics" => Array("value" => "Logistics", "label" => "info", "text" => "Logística"),
        "Non-profits" => Array("value" => "Non-profits", "label" => "info", "text" => "Sem Fins Lucrativos"),
        "Travel" => Array("value" => "Travel", "label" => "info", "text" => "Viagens")
    );


    const SERVICE_CATEGORY = Array(
        "Agricultural Cooperatives" => Array("value" => "Agricultural Cooperatives", "label" => "info", "text" => "Cooperativa Agrícola"),
        "Agricultural Services" => Array("value" => "Agricultural Services", "label" => "info", "text" => "Serviços Agrícolas"),
        "Automobiles" => Array("value" => "Automobiles", "label" => "info", "text" => "Automóveis"),
        "Digital Goods" => Array("value" => "Digital Goods", "label" => "info", "text" => "Bens Digitais"),
        "Physical Goods" => Array("value" => "Physical Goods", "label" => "info", "text" => "Bens Físicos"),
        "Real Estate" => Array("value" => "Real Estate", "label" => "info", "text" => "Imobiliária"),
        "Digital Services" => Array("value" => "Digital Services", "label" => "info", "text" => "Serviços Digitais"),
        "Legal Services" => Array("value" => "Legal Services", "label" => "info", "text" => "Serviços Legais"),
        "Physical Services" => Array("value" => "Physical Services", "label" => "info", "text" => "Serviços Físicos"),
        "Professional Services" => Array("value" => "Professional Services", "label" => "info", "text" => "Prestação de Serviços"),
        "Nursery Schools" => Array("value" => "Nursery Schools", "label" => "info", "text" => "Creche"),
        "Primary Schools" => Array("value" => "Primary Schools", "label" => "info", "text" => "Escola Primária"),
        "Secondary Schools" => Array("value" => "Secondary Schools", "label" => "info", "text" => "Colégios"),
        "Tertiary Institutions" => Array("value" => "Tertiary Institutions", "label" => "info", "text" => "Universidades"),
        "Vocational Training" => Array("value" => "Vocational Training", "label" => "info", "text" => "Formação Profissional"),
        "Virtual Learning" => Array("value" => "Virtual Learning", "label" => "info", "text" => "Ensino a Distância"),
        "Other Educational Services" => Array("value" => "Other Educational Services", "label" => "info", "text" => "Outros Serviços de Educação"),
        "Financial Cooperatives" => Array("value" => "Financial Cooperatives", "label" => "info", "text" => "Cooperativas Financeiras"),
        "Corporate Services" => Array("value" => "Corporate Services", "label" => "info", "text" => "Serviços Corporativos"),
        "Cryptocurrency Exchanges" => Array("value" => "Cryptocurrency Exchanges", "label" => "info", "text" => "Exchanges de Criptomoedas"),
        "Payment Solution Service Providers" => Array("value" => "Payment Solution Service Providers", "label" => "info", "text" => "Intermediação de Pagamentos / Provedores de Pagamento"),
        "Insurance" => Array("value" => "Insurance", "label" => "info", "text" => "Seguros"),
        "Investments" => Array("value" => "Investments", "label" => "info", "text" => "Investimentos"),
        "Agricultural Investments" => Array("value" => "Agricultural Investments", "label" => "info", "text" => "Investimentos Agrícolas"),
        "Lending" => Array("value" => "Lending", "label" => "info", "text" => "Empréstimo"),
        "Bill Payments" => Array("value" => "Bill Payments", "label" => "info", "text" => "Pagamento de contas"),
        "Payroll" => Array("value" => "Payroll", "label" => "info", "text" => "Folha de pagamento"),
        "Remittances" => Array("value" => "Remittances", "label" => "info", "text" => "Remessas Financeiras"),
        "Savings" => Array("value" => "Savings", "label" => "info", "text" => "Poupança"),
        "Mobile Wallets" => Array("value" => "Mobile Wallets", "label" => "info", "text" => "Carteiras Móveis"),
        "Betting" => Array("value" => "Betting", "label" => "info", "text" => "Aposta"),
        "Lotteries" => Array("value" => "Lotteries", "label" => "info", "text" => "Loteria"),
        "Prediction Services" => Array("value" => "Prediction Services", "label" => "info", "text" => "Serviços de Previsão"),
        "Hotels" => Array("value" => "Hotels", "label" => "info", "text" => "Hoteis"),
        "Restaurants" => Array("value" => "Restaurants", "label" => "info", "text" => "Restaurantes"),
        "Gymns" => Array("value" => "Gymns", "label" => "info", "text" => "Academias"),
        "Hospitals" => Array("value" => "Hospitals", "label" => "info", "text" => "Hospitais"),
        "Pharmacies" => Array("value" => "Pharmacies", "label" => "info", "text" => "Farmácias"),
        "Herbal Medicine" => Array("value" => "Herbal Medicine", "label" => "info", "text" => "Fitoterapia"),
        "Telemedicine" => Array("value" => "Telemedicine", "label" => "info", "text" => "Telemedicina"),
        "Cinemas" => Array("value" => "Cinemas", "label" => "info", "text" => "Cinema"),
        "Nightclubs" => Array("value" => "Nightclubs", "label" => "info", "text" => "Casas Noturnas"),
        "Events" => Array("value" => "Events", "label" => "info", "text" => "Eventos"),
        "Press & Media" => Array("value" => "Press & Media", "label" => "info", "text" => "Imprensa, Revistas"),
        "Recreation Centres" => Array("value" => "Recreation Centres", "label" => "info", "text" => "Centros Recreativos"),
        "Streaming Services" => Array("value" => "Streaming Services", "label" => "info", "text" => "Serviços de Streaming"),
        "Professional Associations" => Array("value" => "Professional Associations", "label" => "info", "text" => "Associações Profissionais"),
        "Government Agencies" => Array("value" => "Government Agencies", "label" => "info", "text" => "Agência Governamental"),
        "NGOs" => Array("value" => "NGOs", "label" => "info", "text" => "ONGs"),
        "Political Parties" => Array("value" => "Political Parties", "label" => "info", "text" => "Partidos Políticos"),
        "Religious Organizations" => Array("value" => "Religious Organizations", "label" => "info", "text" => "Organizações Religiosas"),
        "Airlines" => Array("value" => "Airlines", "label" => "info", "text" => "Empresas Aéreas"),
        "Ridesharing" => Array("value" => "Ridesharing", "label" => "info", "text" => "Compartilhamento de Carona"),
        "Tour Services" => Array("value" => "Tour Services", "label" => "info", "text" => "Serviços Turísticos"),
        "Transportation" => Array("value" => "Transportation", "label" => "info", "text" => "Transportes"),
        "Travel Agencies" => Array("value" => "Travel Agencies", "label" => "info", "text" => "Agências de Viagens"),
        "Cable Television" => Array("value" => "Cable Television", "label" => "info", "text" => "TV a Cabo"),
        "Electricity" => Array("value" => "Electricity", "label" => "info", "text" => "Compania Elétrica"),
        "Garbage Diposal" => Array("value" => "Garbage Diposal", "label" => "info", "text" => "Coleta de Lixo"),
        "Internet" => Array("value" => "Internet", "label" => "info", "text" => "Internet"),
        "Telecoms" => Array("value" => "Telecoms", "label" => "info", "text" => "Telecomunicações"),
        "Water" => Array("value" => "Water", "label" => "info", "text" => "Água")
    );

    const BUSINESS_TYPE = Array(
        "Starter Business" => Array("value" => "Starter Business", "label" => "info", "text" => "Startup"),
        "Registered Business" => Array("value" => "Registered Business", "label" => "info", "text" => "Empresa Registrada")
    );

    const REGISTRATION_TYPE = Array(
        "government_instrumentality" => Array("value" => "government_instrumentality", "label" => "info", "text" => "Instrumento Governamental"),
        "governmental_unit" => Array("value" => "governmental_unit", "label" => "info", "text" => "Unidade Governamental"),
        "incorporated_non_profit" => Array("value" => "incorporated_non_profit", "label" => "info", "text" => "Incorporada Sem Fins Lucrativos"),
        "limited_liability_partnership" => Array("value" => "limited_liability_partnership", "label" => "info", "text" => "Sociedade de Responsabilidade Limitada"),
        "multi_member_llc" => Array("value" => "multi_member_llc", "label" => "info", "text" => "Empresa de Responsabilidade Limitada - Mais de um Sócio"),
        "private_company" => Array("value" => "private_company", "label" => "info", "text" => "Empresa Privada"),
        "private_corporation" => Array("value" => "private_corporation", "label" => "info", "text" => "Corporação Privada"),
        "private_partnership" => Array("value" => "private_partnership", "label" => "info", "text" => "Sociedade Privada"),
        "public_company" => Array("value" => "public_company", "label" => "info", "text" => "Empresa Pública"),
        "public_corporation" => Array("value" => "public_corporation", "label" => "info", "text" => "Corporação Pública"),
        "public_partnership" => Array("value" => "public_partnership", "label" => "info", "text" => "Sociedade Pública"),
        "single_member_llc" => Array("value" => "single_member_llc", "label" => "info", "text" => "Empresa de Responsabilidade Limitada - Sócio Único"),
        "sole_proprietorship" => Array("value" => "sole_proprietorship", "label" => "info", "text" => "Propriedade Individual"),
        "tax_exempt_government_instrumentality" => Array("value" => "tax_exempt_government_instrumentality", "label" => "info", "text" => "Instrumento Governamental Isento de Impostos"),
        "unincorporated_association" => Array("value" => "unincorporated_association", "label" => "info", "text" => "Associação Não Constituída"),
        "unincorporated_non_profit" => Array("value" => "unincorporated_non_profit", "label" => "info", "text" => "Organização Sem Fins Lucrativos Não Constituída")
    );
    
    
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    

    public static function getOrCreate($userId) {

        
        $compliance = Compliance::where("user_id", $userId)->first();
        if (!$compliance) {
            $compliance = new Compliance();
            $compliance->user_id = $userId;
            $compliance->save();
        }

        return $compliance;
    }
    
    public function saveCompliance($userId, $dados, $type = "pf") {
        
        try {
            $compliance = self::where("user_id", $userId)->first();
            
            if (!$compliance) {
                $user = User::where("id", $userId)->first();
                
                $dados = Array(
                    "user_id" => $user->id,
                    "status" => 0,
                    "business_status" => 0,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s")
                );
                
                $compliance = Compliance::create($dados);
            }
            
            
            if ($type == "pf") {
                
                $validator = Validator::make($dados, [
                    'first_name' => 'required|string|max:80',
                    'last_name' => 'required|string|max:80',
                    'birthday' => 'required|string|max:10',
                    'cpf' => 'required|string|max:14',
                    'rg' => 'required|string|max:20',
                    'gender' => 'required',
                    'nationality' => 'required',
                    'phone_country_code' => 'required|string',
                    'phone_ddd' => 'required|string|max:3',
                    'phone' => 'required|string|max:10',
                    'mobilephone_country_code' => 'required|string',
                    'mobilephone_ddd' => 'required|string|max:3',
                    'mobilephone' => 'required|string|max:10',
                    'address_zipcode' => 'required|string|max:10',
                    'address' => 'required|string|max:80',
                    'address_number' => 'required|string|max:10',
                    'address_complement' => 'nullable|string|max:40',
                    'neighborhood' => 'required|string|max:40',
                    'address_state' => 'required|string|max:10',
                    'address_city' => 'required|string|max:40',
                    'mother_name' => 'required|string|max:80',
                    'email' => 'required|email|max:80',
                    'address_country_id' => 'required|numeric'
                ]);
                
                if ($validator->fails()) {
                    throw new \Exception($validator->errors());
                }
                
                $birth = explode("/", $dados["birthday"]);
                if (sizeof($birth) != 3) {
                    throw new \Exception("Invalid birthday format.");
                }
                $birthday = "{$birth[2]}-{$birth[1]}-{$birth[0]}";

                $compliance->first_name = $dados["first_name"];
                $compliance->last_name = $dados["last_name"];
                $compliance->cpf = $dados["cpf"];
                $compliance->rg = $dados["rg"];
                $compliance->birthday = $birthday;
                $compliance->email = $dados["email"];
                $compliance->mother_name = $dados["mother_name"];
                $compliance->gender = $dados["gender"];
                $compliance->nationality = $dados["nationality"];
                $compliance->phone_country_code = $dados["phone_country_code"];
                $compliance->phone_ddd = $dados["phone_ddd"];
                $compliance->phone = $dados["phone"];
                $compliance->mobilephone_country_code = $dados["mobilephone_country_code"];
                $compliance->mobilephone_ddd = $dados["mobilephone_ddd"];
                $compliance->mobilephone = $dados["mobilephone"];
                $compliance->address_zipcode = $dados["address_zipcode"];
                $compliance->address = $dados["address"];
                $compliance->address_number = $dados["address_number"];
                $compliance->address_complement = $dados["address_complement"];
                $compliance->neighborhood = $dados["neighborhood"];
                $compliance->address_state = $dados["address_state"];
                $compliance->address_city = $dados["address_city"];
                $compliance->address_country_id = $dados["address_country_id"];
                $compliance->address_type = "RESIDENTIAL";
                $compliance->phone_type = "RESIDENCIAL";
                $compliance->status_personal = "PENDING";
                
            } else {


                $validator = Validator::make($dados, [
                    'website' => 'required|string|max:80',
                    'office_address' => 'required|string|max:80',
                    'business_type' => 'required|string|max:32',
                    'registration_type' => 'required|string|max:255',
                    'industry' => 'required|string|max:255',
                    'category' => 'required|string|max:255',
                    'staff_size' => 'required|string|max:32',
                    'trading_desc' => 'required|string|max:255',
                    'trading_name' => 'required|string|max:255',
                    'legal_name' => 'required|string|max:255',
                    'company_fundation_date' => 'required|string|max:10',
                    'phone_ddd' => 'required|string|max:3',
                    'phone_country_code' => 'required|string|max:3',
                    'phone' => 'required|string|max:20',
                    'company_document_id' => 'required|string|max:20',
                    'month_revenue' => 'required|numeric',
                    'patrimony' => 'required|numeric',
                    'office_address_number' => 'required|string|max:10',
                    'office_address_complement' => 'nullable|string|max:40',
                    'office_address_neighborhood' => 'required|string|max:40',
                    'office_address_postalcode' => 'required|string|max:20',
                    'office_address_city' => 'required|string|max:40',
                    "office_address_state" => 'required|string|max:2',
                    'office_address_country_id' => 'required|numeric',
                    'source_of_funds' => 'required|string|max:1000',
                    'source_of_capital' => 'required|string|max:1000',
                    'source_of_wealth' => 'required|string|max:1000'
                ]);

                if ($validator->fails()) {
                    throw new \Exception($validator->errors());
                }


                $fundation = explode("/", $dados["company_fundation_date"]);
                if (sizeof($fundation) != 3) {
                    throw new \Exception("Invalid fundation day format.");
                }
                $fundationDay = "{$fundation[2]}-{$fundation[1]}-{$fundation[0]}";
                

                $compliance->website = $dados["website"];
                $compliance->office_address = $dados["office_address"];
                $compliance->business_type = $dados["business_type"];
                $compliance->registration_type = $dados["registration_type"];
                $compliance->industry = $dados["industry"];
                $compliance->category = $dados["category"];
                $compliance->staff_size = $dados["staff_size"];
                $compliance->description = $dados["trading_desc"];
                $compliance->trading_name = $dados["trading_name"];
                $compliance->legal_name = $dados["legal_name"];
                $compliance->company_fundation_date = $fundationDay;
                $compliance->business_phone_ddd = $dados["phone_ddd"];
                $compliance->business_phone_country_code = $dados["phone_country_code"];
                $compliance->business_phone = $dados["phone"];
                $compliance->company_document_id = $dados["company_document_id"];
                $compliance->month_revenue = $dados["month_revenue"];
                $compliance->patrimony = $dados["patrimony"];
                $compliance->office_address_number = $dados["office_address_number"];
                $compliance->office_address_complement = $dados["office_address_complement"];
                $compliance->office_address_neighborhood = $dados["office_address_neighborhood"];
                $compliance->office_address_postalcode = $dados["office_address_postalcode"];
                $compliance->office_address_state = $dados["office_address_state"];
                $compliance->office_address_city = $dados["office_address_city"];
                $compliance->office_address_country_id = $dados["office_address_country_id"];
                $compliance->source_of_funds = $dados["source_of_funds"];
                $compliance->business_status = 0;
                $compliance->source_of_capital = $dados["source_of_capital"];
                $compliance->source_of_wealth = $dados["source_of_wealth"];
                $compliance->status_business = "PENDING";

            }
            
            $compliance->save();
            
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
        
    }


    public function updateClientDocumentFiles($userId, $data) {
        
        if (empty($data["id_type"])) {
            throw new \Exception("É necessário informar o tipo de documento enviado.");
        }

        if (empty($data["document"])) {
            throw new \Exception("É necessário informar o número do documento enviado.");
        }

        if (empty($data["idcard"])) {
            throw new \Exception("É necessário informar a frente do documento de identidade.");
        }

        if (empty($data["idcard_back"])) {
            throw new \Exception("É necessário informar o verso do documento de identidade.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if ($compliance->idcard != null) {
            $path = './asset/profile/';
            $link = $path . $compliance->idcard;
            if (file_exists($link)) {
                @unlink($link);
            }
        }
        if ($compliance->idcard_back != null) {
            $path = './asset/profile/';
            $link = $path . $compliance->idcard_back;
            if (file_exists($link)) {
                @unlink($link);
            }
        }

        $compliance->id_type = $data["id_type"];
        $compliance->document = $data["document"];
        $compliance->idcard = $data["idcard"];
        $compliance->idcard_back = $data["idcard_back"];
        $compliance->personal_document_status = "PENDING";

        $compliance->save();
    }



    public function updateClientProofFiles($userId, $proof) {
        
        if (empty($proof)) {
            throw new \Exception("É necessário enviar o comprovante de residência.");
        }


        $compliance = self::where("user_id", $userId)->first();

        if ($compliance->proof != null) {
            $path = './asset/profile/';
            $link = $path . $compliance->proof;
            if (file_exists($link)) {
                @unlink($link);
            }
        }

        $compliance->proof = $proof;
        $compliance->personal_proof_status = "PENDING";

        $compliance->save();
    }


    public function updateClientSelfieFiles($userId, $selfie) {
        
        if (empty($selfie)) {
            throw new \Exception("É necessário enviar a selfie segurando o documento.");
        }


        $compliance = self::where("user_id", $userId)->first();

        if ($compliance->selfie != null) {
            $path = './asset/profile/';
            $link = $path . $compliance->selfie;
            if (file_exists($link)) {
                @unlink($link);
            }
        }

        $compliance->selfie = $selfie;
        $compliance->personal_selfie_status = "PENDING";

        $compliance->save();
    }



    public function updateBusinessProofFiles($userId, $proof) {
        
        if (empty($proof)) {
            throw new \Exception("É necessário enviar o comprovante de endereço.");
        }


        $compliance = self::where("user_id", $userId)->first();

        if ($compliance->business_proof != null) {
            $path = './asset/profile/';
            $link = $path . $compliance->business_proof;
            if (file_exists($link)) {
                @unlink($link);
            }
        }

        $compliance->business_proof = $proof;
        $compliance->business_proof_status = "PENDING";

        $compliance->save();
    }


    public function updateBusinessRegistryFiles($userId, $registry) {
        
        if (empty($registry)) {
            throw new \Exception("É necessário enviar a selfie segurando o documento.");
        }


        $compliance = self::where("user_id", $userId)->first();

        if ($compliance->business_national_registry != null) {
            $path = './asset/profile/';
            $link = $path . $compliance->business_national_registry;
            if (file_exists($link)) {
                @unlink($link);
            }
        }

        $compliance->business_national_registry = $registry;
        $compliance->business_registry_status = "PENDING";

        $compliance->save();
    }


    public static function rejectPersonalCompliance($userId, $message) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        if (empty($message)) {
            throw new \Exception("A mensagem de rejeição deve ser informada.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }
         
        $compliance->status_personal = "REJECTED";
        $compliance->personal_status_msg = $message;
        $compliance->save();

    }


    public static function approvePersonalCompliance($userId) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        if($compliance->personal_document_status != "APPROVED" || $compliance->personal_proof_status != "APPROVED" || $compliance->personal_selfie_status != "APPROVED") {
            throw new \Exception("Todos os documentos devem ser aprovados antes do compliance.");
        }

        $compliance->status_personal = "APPROVED";
        $compliance->save();
    }
    


    public static function rejectPersonalDocument($userId, $message) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        if (empty($message)) {
            throw new \Exception("A mensagem de rejeição deve ser informada.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->personal_document_status = "REJECTED";
        $compliance->personal_document_msg = $message;
        $compliance->save();
    }


    public static function rejectPersonalProof($userId, $message) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        if (empty($message)) {
            throw new \Exception("A mensagem de rejeição deve ser informada.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->personal_proof_status = "REJECTED";
        $compliance->personal_proof_msg = $message;
        $compliance->save();
    }


    public static function rejectPersonalSelfie($userId, $message) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        if (empty($message)) {
            throw new \Exception("A mensagem de rejeição deve ser informada.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->personal_selfie_status = "REJECTED";
        $compliance->personal_selfie_msg = $message;
        $compliance->save();
    }

    public static function approvePersonalDocument($userId) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->personal_document_status = "APPROVED";
        $compliance->save();
    }
    

    public static function approvePersonalProof($userId) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }
        
        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->personal_proof_status = "APPROVED";
        $compliance->save();
    }


    public static function approvePersonalSelfie($userId) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }
        
        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->personal_selfie_status = "APPROVED";
        $compliance->save();
    }





    public static function rejectBusinessCompliance($userId, $message) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        if (empty($message)) {
            throw new \Exception("A mensagem de rejeição deve ser informada.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }
         
        $compliance->status_business = "REJECTED";
        $compliance->business_status_msg = $message;
        $compliance->save();
    }

    

    public static function approveBusinessCompliance($userId) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        if($compliance->business_registry_status != "APPROVED" || $compliance->business_proof_status != "APPROVED") {
            throw new \Exception("Todos os documentos devem ser aprovados antes do compliance.");
        }

        $compliance->status_business = "APPROVED";
        $compliance->save();
    }
    

    public static function rejectBusinessRegistry($userId, $message) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        if (empty($message)) {
            throw new \Exception("A mensagem de rejeição deve ser informada.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->business_registry_status = "REJECTED";
        $compliance->business_registry_msg = $message;
        $compliance->save();
    }

    public static function approveBusinessRegistry($userId) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->business_registry_status = "APPROVED";
        $compliance->save();
    }

    public static function rejectBusinessProof($userId, $message) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        if (empty($message)) {
            throw new \Exception("A mensagem de rejeição deve ser informada.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->business_proof_status = "REJECTED";
        $compliance->business_proof_msg = $message;
        $compliance->save();
    }

    public static function approveBusinessProof($userId) {

        if (!is_numeric($userId) || !($userId > 0)) {
            throw new \Exception("Identificação do usuário inválida.");
        }

        $compliance = self::where("user_id", $userId)->first();

        if (!$compliance) {
            throw new \Exception("Não foi encontrado um processo de compliance válido para o cliente.");
        }

        $compliance->business_proof_status = "APPROVED";
        $compliance->save();
    }


    public static function validateWithdrawLimit($compliance, $amount, $settings = null) {
        if (!$settings) {
            $settings = Settings::first();
        }

        $withdrawLimit = 0;
        if (!$compliance || $compliance->status_personal != "APPROVED" || $compliance->status_business != "APPROVED") {
            $withdrawLimit = $settings->withdraw_limit;
        } else if ($compliance->status_business == "APPROVED") {
            $withdrawLimit = $settings->business_limit;
        } else if ($compliance->status_personal == "APPROVED"){
            $withdrawLimit = $settings->starter_limit;
        }

        $today = date("Y-m-d");
        $old = Withdraw::whereuser_id($compliance->user_id)->where(function ($query) {
            $query->orWhere('status', 0)->orWhere('status', 1);
        })->where("created_at", ">=", "{$today} 00:00:00")->where("created_at", "<=", "{$today} 23:59:59")->sum('amount');

        $total = $amount + $old;
        
        return (object) Array(
            "total_limit" => $withdrawLimit, 
            "available_limit" => ($withdrawLimit - $old), 
            "result_limit" => ($withdrawLimit - $total),
            "valid" => ($total <= $withdrawLimit)
        );
    }
}