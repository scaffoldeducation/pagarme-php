## Introdução

Essa SDK foi construída com o intuito de torná-la flexível, de forma que todos possam utilizar todas as features, de
todas as versões de API.

Você pode acessar a documentação oficial da PagarMe - v5 acessando esse [link](https://docs.pagar.me/v5/docs).

## Requisitos

* PHP versão 8.0 ou maior;
* Guzzle Http versão 7.4 ou maior.

## Índice

- [Introdução](#introdução)
- [Requisitos](#requisitos)
- [Índice](#índice)
- [Instalação](#instalação)
- [Configuração](#configuração)
  - [Definindo headers customizados](#definindo-headers-customizados)
- [Pedidos](#pedidos)
  - [Criando um pedido](#criando-um-pedido)
  - [Incluindo cobrança em pedido aberto](#incluindo-cobrança-em-pedido-aberto)
  - [Fechando um pedido](#fechando-um-pedido)
  - [Retornando pedidos](#retornando-pedidos)
  - [Retornando um pedido](#retornando-um-pedido)
- [Itens em pedidos abertos](#itens-em-pedidos-abertos)
  - [Incluindo item](#incluindo-item)
  - [Atualizando item](#atualizando-item)
  - [Retornando item](#retornando-item)
  - [Deletando item](#deletando-item)
  - [Removendo todos os itens](#removendo-todos-os-itens)
- [Cobranças](#cobranças)
  - [Capturando cobrança](#capturando-cobrança)
  - [Editando cartão de cobrança](#editando-cartão-de-cobrança)
  - [Editando data de vencimento da cobrança](#editando-data-de-vencimento-da-cobrança)
  - [Editando método de pagamento da cobrança](#editando-método-de-pagamento-da-cobrança)
  - [Retornando todas as cobranças](#retornando-todas-as-cobranças)
  - [Retornando uma cobrança](#retornando-uma-cobrança)
  - [Confirmando uma cobrança (cash)](#confirmando-uma-cobrança-cash)
  - [Retentando uma cobrança manualmente](#retentando-uma-cobrança-manualmente)
  - [Cancelando uma cobrança](#cancelando-uma-cobrança)
- [Cartões](#cartões)
  - [Criando um cartão](#criando-um-cartão)
  - [Atualizando um cartão](#atualizando-um-cartão)
  - [Retornando cartões](#retornando-cartões)
  - [Retornando um cartão](#retornando-um-cartão)
  - [Deletando um cartão](#deletando-um-cartão)
- [Clientes](#clientes)
  - [Criando um cliente](#criando-um-cliente)
  - [Atualizando um cliente](#atualizando-um-cliente)
  - [Retornando clientes](#retornando-clientes)
  - [Retornando um cliente](#retornando-um-cliente)
- [Endereços do cliente](#endereços-do-cliente)
  - [Criando um endereço](#criando-um-endereço)
  - [Atualizando um endereço](#atualizando-um-endereço)
  - [Retornando endereços](#retornando-endereços)
  - [Retornando um endereço](#retornando-um-endereço)
  - [Deletando um endereço](#deletando-um-endereço)
- [Tratando exceções](#tratando-exceções)
- [Testes](#testes)

## Instalação

Instale a biblioteca utilizando o comando

`composer require andreoneres/pagarme-php`

## Configuração

Para incluir a biblioteca em seu projeto, basta fazer o seguinte:

```php
<?php
require('vendor/autoload.php');

$pagarme = new PagarMe\Client('SUA_CHAVE_DE_API');
```

### Definindo headers customizados

1. Se necessário for é possível definir headers http customizados para os requests. Para isso basta informá-los durante
   a instanciação do objeto `Client`:

```php
<?php
require('vendor/autoload.php');

$pagarme = new PagarMe\Client(
    'SUA_CHAVE_DE_API',
    ['headers' => ['MEU_HEADER_CUSTOMIZADO' => 'VALOR HEADER CUSTOMIZADO']]
); 
```

E então, você poderá utilizar o cliente para fazer requisições ao Pagar.me, como nos exemplos abaixo.

## Pedidos

Nesta seção será explicado como realizar e manipular pedidos no Pagar.me com essa biblioteca.

### Criando um pedido

```php
<?php
$order = $pagarme->orders()->create([
    'customer' => [
        'name' => 'Nome do cliente',
        'type' => 'individual',
        'email' => 'cliente@email.com',
        'document' => '12345678901',
        'document_type' => 'CPF',
        'gender' => 'female',
        'address' => [
            'country' => 'BR',
            'state' => 'Bahia',
            'city' => 'Barreiras',
            'zip_code' => '00000000',
            'line_1' => '120, Rua dos Anjos, São Gonçalo',
            'line_2' => 'apartamento, 1º andar'
        ],
        'phones' => [
            'home_phone' => [
                'country_code' => '55',
                'area_code' => '77',
                'number' => '999305687'
            ],
            'mobile_phone' => [
                'country_code' => '55',
                'area_code' => '77',
                'number' => '999305688'
            ],    
        ],
        'birthdate' => '2020-10-10'
    ],
    'items' => [
        [
            'amount' => 12000,
            'description' => 'Bike OGGI Azul',
            'quantity' => '3',
            'code' => 'CODIGO_DO_ITEM_NO_SEU_SISTEMA'
        ]       
    ],
    'shipping' => [
        'amount' => '1300',
        'description' => 'Descrição',
        'recipient_name' => 'João Gomes',
        'recipient_phone' => '557999235940',
        'address' => [
            'country' => 'BR',
            'state' => 'Bahia',
            'city' => 'Barreiras',
            'zip_code' => '00000000',
            'line_1' => '120, Rua dos Anjos, São Gonçalo',
            'line_2' => 'apartamento, 1º andar'
        ],
    ],
    'payments' => [
        [
            'payment_method' => 'pix',
            'pix' => [
                'expires_in' => 1000,
                'additional_information' => [
                    [
                        'name' => 'Teste',
                        'value' => 'Este é um teste.'
                    ]
                ]   
            ],
            'amount' => 10000    
        ]
    ],
    'closed' => false,
    'antifraud_enabled' => true
]);
```

### Incluindo cobrança em pedido aberto

```php
<?php
$chargeOrder = $pagarme->orders()->addCharge([
    'order_id' => 'ID_DO_PEDIDO',
    'amount' => 40000,
    'payment' => [
        'payment_method' => 'boleto',
        'boleto' => [
            'bank' => 237,
            'instructions' => 'Instruções do boleto',
            'due_at' => '2022-10-10',
            'nosso_numero' => '242534544',
            'type' => 'DM',
            'document_number' => 'Ident. do boleto'
        ]   
    ],
    'due_at' => '2022-10-10',
    'customer_id' => 'ID_DO_CLIENTE'
]);
```

### Fechando um pedido

```php
<?php
$closedOrder = $pagarme->orders()->closed([
    'id' => 'ID_DO_PEDIDO',
    'status' => 'NOVO_STATUS_PARA_O_PEDIDO'
]);
```

### Retornando pedidos

```php
<?php
$order = $pagarme->orders()->getList();
```

Se preferir, é possível utilizar parâmetros para filtrar essa busca, por exemplo, se quiser filtrar apenas pedidos 
pagas, você pode utilizar o código abaixo:

```php
<?php
$order = $pagarme->orders()->getList([
    'status' => 'paid'
]);
```

### Retornando um pedido

```php
<?php
$order = $pagarme->orders()->get([
    'id' => 'ID_DO_PEDIDO'
]);
```

## Itens em pedidos abertos

Com a criação de um pedido aberto, é possível que os itens sejam gerenciados.

### Incluindo item

```php
<?php
$orderItem = $pagarme->orderItems()->create([
    'order_id' => 'ID_DO_PEDIDO',
    'amount' => 12234,
    'description' => 'Descrição do item',
    'quantity' => '4',
    'category' => 'Bikes'
]);
```

### Atualizando item

```php
<?php
$orderItemUpdated = $pagarme->orderItems()->update([
    'order_id' => 'ID_DO_PEDIDO',
    'item_id' => 'ID_DO_ITEM_DO_PEDIDO',
    'amount' => 12235,
    'description' => 'Descrição do item',
    'quantity' => '4',
    'category' => 'Bikes'
]);
```

### Retornando item

```php
<?php
$orderItem = $pagarme->orderItems()->get([
    'order_id' => 'ID_DO_PEDIDO',
    'item_id' => 'ID_DO_ITEM_DO_PEDIDO'
]);
```

### Deletando item

```php
<?php
$orderItemDeleted = $pagarme->orderItems()->delete([
    'order_id' => 'ID_DO_PEDIDO',
    'item_id' => 'ID_DO_ITEM_DO_PEDIDO'
]);
```

### Removendo todos os itens

```php
<?php
$orderItemsDeleted = $pagarme->orderItems()->deleteAll([
    'order_id' => 'ID_DO_PEDIDO'
]);
```

## Cobranças

A cobrança é sempre a base de um pagamento. Desta forma, ela pode ser gerada por pedidos e assinaturas.

### Capturando cobrança

```php
<?php
$charge = $pagarme->charges()->capture([
    'charge_id' => 'ID_DA_COBRANCA',
    'amount' => '10000',
    'code' => 'CODIGO_DA_COBRANCA_NO_SEU_SISTEMA'
]);
```

### Editando cartão de cobrança

```php
<?php
$chargeUpdated = $pagarme->charges()->updateCard([
    'charge_id' => 'ID_DA_COBRANCA',
    'update_subscription' => false,
    'card_id' => 'ID_DO_CARTAO',
    'card_token' => 'TOKEN_DO_CARTAO'
]);
```

### Editando data de vencimento da cobrança

```php
<?php
$chargeUpdated = $pagarme->charges()->updateBillingDue([
    'charge_id' => 'ID_DA_COBRANCA',
    'due_at' => '2022-10-10'
]);
```

### Editando método de pagamento da cobrança

```php
<?php
$chargeUpdated = $pagarme->charges()->updatePaymentMethod([
    'charge_id' => 'ID_DA_COBRANCA',
    'update_subscription' => false,
    'payment_method' => 'pix',
    'pix' => [
        'expires_in' => 1000,
        'additional_information' => [
        [
            'name' => 'Teste',
            'value' => 'Este é um teste.' 
        ]
    ]
]);
```

### Retornando todas as cobranças

```php
<?php
$charges = $pagarme->charges()->getList();
```

Se preferir, é possível utilizar parâmetros para filtrar essa busca, por exemplo, se quiser filtrar apenas cobranças
pagas, você pode utilizar o código abaixo:

```php
<?php
$charges = $pagarme->charges()->getList([
    'status' => 'paid'
]);
```

### Retornando uma cobrança

```php
<?php
$charge = $pagarme->charges()->get([
    'id' => 'ID_DA_COBRANCA'
]);
```

### Confirmando uma cobrança (cash)

```php
<?php
$confirmedCharge = $pagarme->charges()->confirmCash([
    'charge_id' => 'ID_DA_COBRANCA',
    'amount' => 12322,
    'code' => 'CODIGO_DA_COBRANCA_NO_SEU_SISTEMA',
    'description' => 'Descrição'
]);
```

### Retentando uma cobrança manualmente

```php
<?php
$charge = $pagarme->charges()->holdCharge([
    'id' => 'ID_DA_COBRANCA'
]);
```

### Cancelando uma cobrança

```php
<?php
$canceledCharge = $pagarme->charges()->cancel([
    'charge_id' => 'ID_DA_COBRANCA',
    'amount' => 1232213
]);
```

## Cartões

Sempre que você faz uma requisição através da nossa API, nós guardamos as informações do portador do cartão, para que,
futuramente, você possa utilizá-las em novas cobranças, ou até mesmo implementar features como one-click-buy.

### Criando um cartão

```php
<?php
$card = $pagarme->cards()->create([
    'customer_id' => 'ID_DO_CLIENTE',
    'holder_name' => 'Yoda',
    'number' => '4242424242424242',
    'exp_month' => '12',
    'exp_year' => '2029',
    'brand' => 'Mastercard',
    'label' => 'Label do cartão',
    'billing_address_id' => 'ID_DO_ENDERECO_DE_PAGAMENTO',
    'cvv' => '123',
    'options' => [
        'verify_card' => true
    ],
]);
```

### Atualizando um cartão

```php
<?php
$cardUpdated = $pagarme->cards()->create([
    'card_id' => 'ID_DO_CARTAO',
    'customer_id' => 'ID_DO_CLIENTE',
    'holder_name' => 'Yoda',
    'exp_month' => '12',
    'exp_year' => '2029',
    'billing_address_id' => 'ID_DO_ENDERECO_DE_PAGAMENTO'
]);
```

### Retornando cartões

```php
<?php
$cards = $pagarme->cards()->getList([
    'customer_id' => 'ID_DO_CLIENTE'
]);
```

### Retornando um cartão

```php
<?php
$card = $pagarme->cards()->get([
    'card_id' => 'ID_DO_CARTÃO',
    'customer_id' => 'ID_DO_CLIENTE'
]);
```

### Deletando um cartão

```php
<?php
$card = $pagarme->cards()->delete([
    'customer_id' => 'ID_DO_CLIENTE',
    'card_id' => 'ID_DO_CARTÃO'
]);
```

## Clientes

Clientes representam os usuários de sua loja, ou negócio. Este objeto contém informações sobre eles, como nome, e-mail e
telefone, além de outros campos.

### Criando um cliente

```php
<?php
$customer = $pagarme->customers()->create([
    'code' => 231432,
    'name' => 'Nome do cliente',
    'type' => 'individual',
    'email' => 'cliente@email.com'
    'document' => '12345678901',
    'document_type' => 'CPF'
    'gender' => 'female',
    'address' => [
        'country' => 'BR',
        'state' => 'Bahia',
        'city' => 'Barreiras',
        'zip_code' => '00000000',
        'line_1' => '120, Rua dos Anjos, São Gonçalo',
        'line_2' => 'apartamento, 1º andar'
    ],
    'phones' => [
        'home_phone' => [
            'country_code' => '55',
            'area_code' => '77',
            'number' => '999305687'
        ],
        'mobile_phone' => [
            'country_code' => '55',
            'area_code' => '77',
            'number' => '999305688'
        ],    
    ],
    'birthdate' => '2020-10-10'
]);
```

### Atualizando um cliente

```php
<?php
$customerUpdated = $pagarme->customers()->update([
    'customer_id' => 231432,
    'name' => 'Nome do cliente',
    'type' => 'individual',
    'email' => 'cliente@email.com'
    'document' => '12345678901',
    'document_type' => 'CPF'
    'gender' => 'female',
    'address' => [
        'country' => 'BR',
        'state' => 'Bahia',
        'city' => 'Barreiras',
        'zip_code' => '00000000',
        'line_1' => '120, Rua dos Anjos, São Gonçalo',
        'line_2' => 'apartamento, 1º andar'
    ],
    'phones' => [
        'home_phone' => [
            'country_code' => '55',
            'area_code' => '77',
            'number' => '999305687'
        ],
        'mobile_phone' => [
            'country_code' => '55',
            'area_code' => '77',
            'number' => '999305688'
        ],    
    ],
    'birthdate' => '2020-10-10'
]);
```

### Retornando clientes

```php
<?php
$customers = $pagarme->customers()->getList();
```

### Retornando um cliente

```php
<?php
$customer = $pagarme->customers()->get([
    'id' => 'ID_DO_CLIENTE'
]);
```

## Endereços do cliente

Seu cliente pode ter um ou vários endereços cadastrados, sendo assim, você poderá manipulá-los através desta seção.

### Criando um endereço

```php
<?php
$address = $pagarme->addresses()->create([
    'customer_id' => 'ID_DO_CLIENTE',
    'country' => 'BR',
    'state' => 'Bahia',
    'city' => 'Barreiras',
    'zip_code' => '00000000',
    'line_1' => '120, Rua dos Anjos, São Gonçalo',
    'line_2' => 'apartamento, 1º andar'
]);
```

### Atualizando um endereço

```php
<?php
$addressUpdated = $pagarme->addresses()->update([
    'address_id' => 'ID_DO_ENDERECO',
    'customer_id' => 'ID_DO_CLIENTE',
    'line_2' => 'apartamento, 1º andar'
]);
```

### Retornando endereços

```php
<?php
$address = $pagarme->addresses()->getList([
    'customer_id' => 'ID_DO_CLIENTE'
]);
```

### Retornando um endereço

```php
<?php
$address = $pagarme->addresses()->get([
    'address_id' => 'ID_DO_ENDERECO',
    'customer_id' => 'ID_DO_CLIENTE'
]);
```

### Deletando um endereço

```php
<?php
$address = $pagarme->addresses()->delete([
    'address_id' => 'ID_DO_ENDERECO',
    'customer_id' => 'ID_DO_CLIENTE'
]);
```

## Tratando exceções

Caso a API retorne um erro, a biblioteca irá lançar uma exceção do tipo `PagarMe\Exceptions\PagarMeException`.
Para capturar esta exceção, você deve utilizar o bloco `try/catch` e tratar o erro da forma que desejar.

Exemplo:

```php
try {
    $customers = $pagarme->customers()->getList();
} catch (BrasilApiException $e) {
    echo $e->getMessage(); // Retorna a mensagem de erro da API
    echo $e->getCode(); // Retorna o código HTTP da API
    echo $e->getErrors(); // Retorna os erros retornados pela API
    echo $e->getRawResponse(); // Retorna a resposta bruta da API
}
```

## Testes

Neste projeto é utilizado o [PHPUnit](https://phpunit.de) para a implementação de testes automatizados.
Para rodá-los, execute o seguinte comando:

```bash
composer test
```
