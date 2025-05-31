## ASAAS - Sistema de pagamentos exemplo
Serviço utilizado para realizar a integração com os pagamentos por PIX, BOLETO e CARTÃO DE CRÉDITO.

Produção: https://www.asaas.com/

Homologação: https://sandbox.asaas.com/

## Instalação e Configuração

Clone o repositório em uma pasta
```
git clone https://github.com/lucaseduardofarias/starter-kit-laravel-12.git
```

Acesse a pasta do projeto
```
cd nome-do-projeto
```
Instale as dependência
```
composer require laravel/sail --dev
```

Faça uma cópia do arquivo de configuração
```
cp .env.example .env
```
Abra o arquivo .env e definida as configurações da base de dados e URL do AMBIENTE e a API KEY gerada na ASAAS

```
DB_CONNECTION=mysql``
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

ASAAS_DOMAIN_API=https://api-sandbox.asaas.com/v3/
ASAAS_KEY=
```

Gere a chave da aplicação:
```
./vendor/bin/sail artisan key:generate
```

Executar o Sail pela primeira vez
```
./vendor/bin/sail up -d
```

Execute a migração da base de dados
```
./vendor/bin/sail artisan migrate
```

Rode para instalar dependencias
```
./vendor/bin/sail composer install
./vendor/bin/sail npm install
```

Em um terminal a parte deixe rodando
```
./vendor/bin/sail npm run dev
```

Não se esqueça de definir as permissões (caso necessário) nas pasta /bootstrap, /storage, /database.

Acesse [localho](http://localhost/) e criei sua conta, que depois será direcionado para http://localhost/dashboard

## Demonstração das telas (Componentes)

![image](https://github.com/user-attachments/assets/c7f2b0a0-a66d-43b4-ba24-5d14f370fc82)

![image](https://github.com/user-attachments/assets/cde1332c-86be-4018-af32-e67156411ee8)

![image](https://github.com/user-attachments/assets/8dfeb92c-acea-47d4-9de6-a7f7f1df7580)

![image](https://github.com/user-attachments/assets/59763170-1c10-488b-b0c6-6f89cb7d6ca4)

![image](https://github.com/user-attachments/assets/e3563a69-b2ed-464c-9707-6b0b4e21f68a)

![image](https://github.com/user-attachments/assets/7f901887-04df-4fee-870d-6cbd9babf89c)

![image](https://github.com/user-attachments/assets/9a695b3c-9f84-4bab-950c-afb4cf6d3c94)

![image](https://github.com/user-attachments/assets/3cc69d41-362d-4bf2-a3b5-6b441fa4204e)

