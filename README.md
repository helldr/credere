# Teste CREDERE
Abaixo segue instruções para o acesso à api web, assim como para instalação local.
O sistema foi construído em PHP, utilizando o framework Laravel (*https://laravel.com/*) , banco de dados MySQL e servidor NGINX.
Você pode usar o Postman (*https://www.postman.com/*) para fazer as chamadas à API.

# Api Web

Está hospedada em um contêiner no DigitalOcean.
Endereço: http://188.166.65.159:8000 

## Utilização

- **Content-Type**: application/json
 - **Accept**: application/json

HTTP requisição | Descrição | Exemplo
------------- | ------------- | -------------
**POST** /api/move | Move a sonda | *http://188.166.65.159:8000/api/move*
**GET** /api/coords | Retorna a posição da sonda | *http://188.166.65.159:8000/api/coords*
**POST** /api/init | Move a sonda para a posição inicial | *http://188.166.65.159:8000/api/init*


** Durante o deploy, uma sonda é criada. Não estava no escopo criação de novas sondas

### **POST** /api/move
Move a sonda para a posição desejada

- ### Parametros esperados

Nome | Tipo | Descrição | Exemplo
------------- | ------------- | ------------- | -------------
 **movimentos** | **Array** | body | {"movimentos": ["GD","M", "M"]}

### Body
```json
{
    "movimentos": ["GD","M", "M"]
}
```
### Exemplo de resposta
```json
{
  "x": 2,
  "y": 3,
  "face": "D"
}
```
### Exceções
```json
{'erro' => 'Um movimento inválido foi detectado. Tente novamente.'}
{'erro' => 'A requisição deve ser um array.'}
{'erro' => 'O comando fornecido ":comando" não é um comando válido.'}

```

### **POST** /api/init
Move a sonda para a posição inicial

- ### Parametros esperados

- **Nenhum**

### Exemplo de resposta
```json
{
    "mensagem":  "A sonda retornou à posição inicial."
}
```

### **GET** /api/coords
Retorna a posição da sonda

- ### Parametros esperados

- **Nenhum**

### Exemplo de resposta
```json
{
    "x":0,
    "y":5,
    "face":"B"
}
```


##

# Instalação local
Recomendo usar o Docker para facilitar o processo. No site oficial *https://www.docker.com/* há um bom guia de como proceder a instalação.
Você também vai precisar do Composer (*https://getcomposer.org/*) instalado e do git (*https://git-scm.com/*), para poder clonar o projeto. 


Considerando que seu ambiente atende aos requisitos acima, acesse o endereço do repositório *https://github.com/helldr/credere* e faça um clone dele em um diretório de sua preferência.

- git clone https://github.com/helldr/credere.git
Isso irá criar uma cópia do sistema dentro da pasta 'credere'. Entre na pasta e difite o seguinte comando abaixo (Mac/Unix)
- cp _env .env
Ou, no windows, copie ou renomeie o arquivo _env para .env. Dentro dessa mesma pasta, execute o composer install
- composer install
Em seguida, é hora de montar a imagem do docker
- docker-compose build
E então, subir o ambiente:
- docker-compose up -d
A seguir, precisamos executar algumas ações no Laravel, que já está rodando dentro do contêiner. Os comandos abaixo servem, na ordem, para criar as variáveis de ambiente do Laravel, construir o banco de dados e popular com nossa sonda:
- docker-compose exec app php artisan config:clear
- docker-compose exec app php artisan migrate
- docker-compose exec app php artisan db:seed

Tudo pronto. Se tudo correu bem, seu contêiner estará acessível em *http://localhost:8000*

Utilize o Postman para testar a API.

## Utilização local

A única diferença é o endereço a ser chamado

- **Content-Type**: application/json
 - **Accept**: application/json

HTTP requisição | Descrição | Exemplo
------------- | ------------- | -------------
**POST** /api/move | Move a sonda | *http://localhost:8000/api/move*
**GET** /api/coords | Retorna a posição da sonda | *http://localhost:8000/api/coords*
**POST** /api/init | Move a sonda para a posição inicial | *http://localhost:8000/api/init*


## Testes

Os feature tests validam as respostas da API.  
- docker-compose exec app php artisan test

### Lembre-se que os comandos 'docker-compose' devem ser executados dentro da pasta de instalação do sistema


### Autor
Helder C Nascimento