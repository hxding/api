---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#general


<!-- START_edbc81716cdf79ccf26838e9fbfd8679 -->
## /create_customer
> Example request:

```bash
curl -X POST "/create_customer" 
```

```javascript
const url = new URL("/create_customer");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST /create_customer`


<!-- END_edbc81716cdf79ccf26838e9fbfd8679 -->

<!-- START_a2e90e7ea93a9da4034cf5699a7efcc6 -->
## /deposit_channel
> Example request:

```bash
curl -X GET -G "/deposit_channel" 
```

```javascript
const url = new URL("/deposit_channel");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 422,
    "message": {
        "sign": [
            "validation.required"
        ],
        "product_id": [
            "validation.required"
        ]
    },
    "data": []
}
```

### HTTP Request
`GET /deposit_channel`


<!-- END_a2e90e7ea93a9da4034cf5699a7efcc6 -->

<!-- START_71b09e82897abac02667943d941280bb -->
## /paylimit
> Example request:

```bash
curl -X POST "/paylimit" 
```

```javascript
const url = new URL("/paylimit");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST /paylimit`


<!-- END_71b09e82897abac02667943d941280bb -->

<!-- START_9a28ba9b0958643d796c8dbdbdcefa5b -->
## /pay
> Example request:

```bash
curl -X POST "/pay" 
```

```javascript
const url = new URL("/pay");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST /pay`


<!-- END_9a28ba9b0958643d796c8dbdbdcefa5b -->

<!-- START_d1930b4dfbf80e9a03c493eb54278a85 -->
## /withdraw_channel
> Example request:

```bash
curl -X GET -G "/withdraw_channel" 
```

```javascript
const url = new URL("/withdraw_channel");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 422,
    "message": {
        "sign": [
            "validation.required"
        ],
        "product_id": [
            "validation.required"
        ]
    },
    "data": []
}
```

### HTTP Request
`GET /withdraw_channel`


<!-- END_d1930b4dfbf80e9a03c493eb54278a85 -->

<!-- START_ee87ea25a2d689c0375dd4c7fe345aa2 -->
## /bank_list
> Example request:

```bash
curl -X GET -G "/bank_list" 
```

```javascript
const url = new URL("/bank_list");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 422,
    "message": {
        "sign": [
            "validation.required"
        ],
        "product_id": [
            "validation.required"
        ]
    },
    "data": []
}
```

### HTTP Request
`GET /bank_list`


<!-- END_ee87ea25a2d689c0375dd4c7fe345aa2 -->

<!-- START_f07838f1cb56836a75b23304719ada6e -->
## /province_list
> Example request:

```bash
curl -X GET -G "/province_list" 
```

```javascript
const url = new URL("/province_list");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 422,
    "message": {
        "sign": [
            "validation.required"
        ],
        "product_id": [
            "validation.required"
        ]
    },
    "data": []
}
```

### HTTP Request
`GET /province_list`


<!-- END_f07838f1cb56836a75b23304719ada6e -->

<!-- START_574940295303040f0e4f218e9d2a45a1 -->
## /province_cities
> Example request:

```bash
curl -X GET -G "/province_cities" 
```

```javascript
const url = new URL("/province_cities");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 422,
    "message": {
        "sign": [
            "validation.required"
        ],
        "product_id": [
            "validation.required"
        ]
    },
    "data": []
}
```

### HTTP Request
`GET /province_cities`


<!-- END_574940295303040f0e4f218e9d2a45a1 -->

<!-- START_02da8c9a51e6da81a00590a85da6a23b -->
## /cities_country
> Example request:

```bash
curl -X GET -G "/cities_country" 
```

```javascript
const url = new URL("/cities_country");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 422,
    "message": {
        "sign": [
            "validation.required"
        ],
        "product_id": [
            "validation.required"
        ]
    },
    "data": []
}
```

### HTTP Request
`GET /cities_country`


<!-- END_02da8c9a51e6da81a00590a85da6a23b -->

<!-- START_5891c82b08b1dedfeddb31258c55c775 -->
## 绑定银行卡

> Example request:

```bash
curl -X POST "/customer_bank" 
```

```javascript
const url = new URL("/customer_bank");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST /customer_bank`


<!-- END_5891c82b08b1dedfeddb31258c55c775 -->

<!-- START_efa0ac041cf89849009fa32230b7ffbb -->
## 获取客户银行卡信息

> Example request:

```bash
curl -X GET -G "/customer_bank" 
```

```javascript
const url = new URL("/customer_bank");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 422,
    "message": {
        "sign": [
            "validation.required"
        ],
        "product_id": [
            "validation.required"
        ]
    },
    "data": []
}
```

### HTTP Request
`GET /customer_bank`


<!-- END_efa0ac041cf89849009fa32230b7ffbb -->

<!-- START_f960c3216af79f3706c7e1fae6adf97a -->
## 取款逻辑

> Example request:

```bash
curl -X POST "/do_withdraw" 
```

```javascript
const url = new URL("/do_withdraw");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST /do_withdraw`


<!-- END_f960c3216af79f3706c7e1fae6adf97a -->

<!-- START_296992fdca2f98e0d6bb5630b678ced0 -->
## 获取TOKEN

> Example request:

```bash
curl -X POST "/token" 
```

```javascript
const url = new URL("/token");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST /token`


<!-- END_296992fdca2f98e0d6bb5630b678ced0 -->

<!-- START_471c74eef82a0997ba36b90a01758a5d -->
## 查询会员信息

> Example request:

```bash
curl -X GET -G "/customer" 
```

```javascript
const url = new URL("/customer");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 403,
    "message": "forbidden",
    "data": []
}
```

### HTTP Request
`GET /customer`


<!-- END_471c74eef82a0997ba36b90a01758a5d -->

<!-- START_5a8a8f6fd5431d7c6172dbc5896daf4f -->
## 创建收款账号

> Example request:

```bash
curl -X POST "/create_bank_account" 
```

```javascript
const url = new URL("/create_bank_account");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST /create_bank_account`


<!-- END_5a8a8f6fd5431d7c6172dbc5896daf4f -->

<!-- START_f53525be9581e23a522c20cb67278eae -->
## 删除收款账号

> Example request:

```bash
curl -X DELETE "/delete_bank_account" 
```

```javascript
const url = new URL("/delete_bank_account");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE /delete_bank_account`


<!-- END_f53525be9581e23a522c20cb67278eae -->

<!-- START_73fca0559bc0e6e6d12c334f50803355 -->
## 读取存款提案

> Example request:

```bash
curl -X GET -G "/deposit_record" 
```

```javascript
const url = new URL("/deposit_record");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 403,
    "message": "forbidden",
    "data": []
}
```

### HTTP Request
`GET /deposit_record`


<!-- END_73fca0559bc0e6e6d12c334f50803355 -->

<!-- START_38e46d0e9530d45f612f50a5f7defdad -->
## 读取取款提案

> Example request:

```bash
curl -X GET -G "/withdraw_record" 
```

```javascript
const url = new URL("/withdraw_record");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "code": 403,
    "message": "forbidden",
    "data": []
}
```

### HTTP Request
`GET /withdraw_record`


<!-- END_38e46d0e9530d45f612f50a5f7defdad -->

<!-- START_b479670630e1f9eeb0bd9ee1a6ba2a6f -->
## 审核存（人工）取款提案

> Example request:

```bash
curl -X PUT "/approve" 
```

```javascript
const url = new URL("/approve");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT /approve`


<!-- END_b479670630e1f9eeb0bd9ee1a6ba2a6f -->

<!-- START_2129b2236a5f3a0a2e9aff03c4d9ae97 -->
## 在线支付存款

> Example request:

```bash
curl -X PUT "/pay" 
```

```javascript
const url = new URL("/pay");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT /pay`


<!-- END_2129b2236a5f3a0a2e9aff03c4d9ae97 -->


