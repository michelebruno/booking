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
[Get Postman Collection]({{URL}}/docs/collection.json)

<!-- END_INFO -->

#Cliente


<!-- START_c4de4e5c7d472d0cfc3f02fc716a5983 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/clienti" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/clienti"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

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
    "current_page": 1,
    "data": [
        {
            "ruolo": "cliente",
            "email": "cliente@example.com",
            "updated_at": "2020-04-19 00:29:11",
            "created_at": "2020-04-19 00:29:11",
            "abilitato": true,
            "_links": {
                "self": "\/clienti\/5e9b9b57f52800006e007fc3",
                "forceDelete": "\/clienti\/5e9b9b57f52800006e007fc3\/forceDelete"
            }
        }
    ],
    "first_page_url": "http:\/\/localhost\/api\/v1\/clienti?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/localhost\/api\/v1\/clienti?page=1",
    "next_page_url": null,
    "path": "http:\/\/localhost\/api\/v1\/clienti",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

### HTTP Request
`GET api/v1/clienti`


<!-- END_c4de4e5c7d472d0cfc3f02fc716a5983 -->

<!-- START_f016b12b068f31b62ab3c5a12733d54b -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/clienti" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/clienti"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/clienti`


<!-- END_f016b12b068f31b62ab3c5a12733d54b -->

<!-- START_4d68d3532a74f177b9ea3f4fe1903280 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/clienti/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/clienti/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Cliente] 1"
}
```

### HTTP Request
`GET api/v1/clienti/{cliente}`


<!-- END_4d68d3532a74f177b9ea3f4fe1903280 -->

<!-- START_ae422952ccef31dbd7838cccbbf22767 -->
## Update the specified resource in storage.

* Non deve essere possibile cambiare il campo ruolo.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/clienti/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/clienti/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/clienti/{cliente}`

`PATCH api/v1/clienti/{cliente}`


<!-- END_ae422952ccef31dbd7838cccbbf22767 -->

<!-- START_fe71aa9ab9a93e5dd5b571b26f58f3ee -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/clienti/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/clienti/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/clienti/{cliente}`


<!-- END_fe71aa9ab9a93e5dd5b571b26f58f3ee -->

<!-- START_87148ed21903b5c06033e0705cc3ad36 -->
## api/v1/clienti/{cliente}/forceDelete
> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/clienti/1/forceDelete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/clienti/1/forceDelete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/clienti/{cliente}/forceDelete`


<!-- END_87148ed21903b5c06033e0705cc3ad36 -->

#Deal


<!-- START_1615b4f8ec884d233061cd406200e6bb -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/deals" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

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
    "current_page": 1,
    "data": [
        {
            "_id": "5e9b880efb7c000022002ec5",
            "tipo": "deal",
            "stato": "pubblico",
            "titolo": "Deal",
            "descrizione": null,
            "disponibili": "20",
            "codice": "D-1",
            "iva": 22,
            "tariffe": {
                "intero": {
                    "importo": "10",
                    "imponibile": 8.2,
                    "slug": "intero",
                    "nome": "Intero",
                    "iva_ereditata": false,
                    "tariffa": {
                        "id": 1,
                        "slug": "intero",
                        "nome": "Intero"
                    }
                }
            },
            "condensato": "D-1 - Deal |  €8.2",
            "cestinato": false,
            "_links": {
                "self": "\/deals\/D-1",
                "tariffe": "\/deals\/D-1\/tariffe",
                "forniture": "\/deals\/D-1\/forniture"
            }
        }
    ],
    "first_page_url": "http:\/\/localhost\/api\/v1\/deals?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/localhost\/api\/v1\/deals?page=1",
    "next_page_url": null,
    "path": "http:\/\/localhost\/api\/v1\/deals",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

### HTTP Request
`GET api/v1/deals`


<!-- END_1615b4f8ec884d233061cd406200e6bb -->

<!-- START_21892033c98146ff71eac80c510e9702 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/deals" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/deals`


<!-- END_21892033c98146ff71eac80c510e9702 -->

<!-- START_743dfeff235d09c24ac545b1cfe2838f -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/deals/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/deals/{deal}`


<!-- END_743dfeff235d09c24ac545b1cfe2838f -->

<!-- START_88c6785104e9d8151458a4e11dfcf99a -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/deals/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/deals/{deal}`

`PATCH api/v1/deals/{deal}`


<!-- END_88c6785104e9d8151458a4e11dfcf99a -->

<!-- START_617770d3b2bda46754d2ed5d20e75f1a -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/deals/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/deals/{deal}`


<!-- END_617770d3b2bda46754d2ed5d20e75f1a -->

<!-- START_8156d6bcb82ab3e64ca2f132090d65f9 -->
## api/v1/deals/{deal}/restore
> Example request:

```bash
curl -X PATCH \
    "{{URL}}/api/v1/deals/1/restore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/restore"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PATCH",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PATCH api/v1/deals/{deal}/restore`


<!-- END_8156d6bcb82ab3e64ca2f132090d65f9 -->

<!-- START_3cdbdf8014b29aa039a7569f567c5557 -->
## Display a listing of the resource.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/deals/1/tariffe" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/tariffe"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/deals/{deal}/tariffe`


<!-- END_3cdbdf8014b29aa039a7569f567c5557 -->

<!-- START_9a5b52b379a7401ecd2eddf68cdda6c6 -->
## Store a newly created resource in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/deals/1/tariffe" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/tariffe"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/deals/{deal}/tariffe`


<!-- END_9a5b52b379a7401ecd2eddf68cdda6c6 -->

<!-- START_194e2a9b316758a2b3633d5754cbd6b7 -->
## Display the specified resource.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/deals/1/tariffe/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/tariffe/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/deals/{deal}/tariffe/{variante}`


<!-- END_194e2a9b316758a2b3633d5754cbd6b7 -->

<!-- START_b00f5a4410b481caffff65e5a69396bc -->
## Update the specified resource in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/deals/1/tariffe/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/tariffe/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/deals/{deal}/tariffe/{variante}`

`PATCH api/v1/deals/{deal}/tariffe/{variante}`


<!-- END_b00f5a4410b481caffff65e5a69396bc -->

<!-- START_3d5637444e2dca712aef86c1a9a7ef53 -->
## Remove the specified resource from storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/deals/1/tariffe/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/tariffe/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/deals/{deal}/tariffe/{variante}`


<!-- END_3d5637444e2dca712aef86c1a9a7ef53 -->

#Deal - Forniture


<!-- START_92008ed9161585e13439fe34c78de4be -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/deals/1/forniture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/forniture"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/deals/{deal}/forniture`


<!-- END_92008ed9161585e13439fe34c78de4be -->

<!-- START_dc78683f82b85585d229721feaba67e4 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/deals/1/forniture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/forniture"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/deals/{deal}/forniture`


<!-- END_dc78683f82b85585d229721feaba67e4 -->

<!-- START_c35c4c802d50fd55d21f5d3a8624280f -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/deals/1/forniture/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/forniture/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/deals/{deal}/forniture/{fornitura}`


<!-- END_c35c4c802d50fd55d21f5d3a8624280f -->

<!-- START_7eb79c2ca53093642579c3122c20c3b3 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/deals/1/forniture/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/forniture/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/deals/{deal}/forniture/{fornitura}`

`PATCH api/v1/deals/{deal}/forniture/{fornitura}`


<!-- END_7eb79c2ca53093642579c3122c20c3b3 -->

<!-- START_637fd758dca7da4c376eab189dade271 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/deals/1/forniture/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/deals/1/forniture/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/deals/{deal}/forniture/{fornitura}`


<!-- END_637fd758dca7da4c376eab189dade271 -->

#Fornitori


<!-- START_1a11f60a8d171668ce3cfda784fe49a9 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/fornitori" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/fornitori`


<!-- END_1a11f60a8d171668ce3cfda784fe49a9 -->

<!-- START_adfe1bfb11534a676c5b64db84700cd1 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/fornitori" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/fornitori`


<!-- END_adfe1bfb11534a676c5b64db84700cd1 -->

<!-- START_d779498449211ff2f6a942d5a85cd098 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/fornitori/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/fornitori/{fornitore}`


<!-- END_d779498449211ff2f6a942d5a85cd098 -->

<!-- START_5dc3aed89d947d4fc5f619fc9b027171 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/fornitori/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/fornitori/{fornitore}`

`PATCH api/v1/fornitori/{fornitore}`


<!-- END_5dc3aed89d947d4fc5f619fc9b027171 -->

<!-- START_7ef072b964f30f1848ef8a6634f5c964 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/fornitori/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/fornitori/{fornitore}`


<!-- END_7ef072b964f30f1848ef8a6634f5c964 -->

<!-- START_2ffee081498e2f075fd35fa39e90806a -->
## api/v1/fornitori/{fornitore}/restore
> Example request:

```bash
curl -X PATCH \
    "{{URL}}/api/v1/fornitori/1/restore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/restore"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PATCH",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PATCH api/v1/fornitori/{fornitore}/restore`


<!-- END_2ffee081498e2f075fd35fa39e90806a -->

#Fornitori - Forniture


<!-- START_5515f2fd06803059e05041492f7dc078 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/fornitori/1/forniture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/fornitori/{fornitore}/forniture`


<!-- END_5515f2fd06803059e05041492f7dc078 -->

<!-- START_96ce3998283fe5e752e8470855d32000 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/fornitori/1/forniture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/fornitori/{fornitore}/forniture`


<!-- END_96ce3998283fe5e752e8470855d32000 -->

<!-- START_a7a155d36325a7b645867cf7087bcce0 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/fornitori/1/forniture/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/fornitori/{fornitore}/forniture/{fornitura}`


<!-- END_a7a155d36325a7b645867cf7087bcce0 -->

<!-- START_1aa5475ec60a9dd66087785b287a7903 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/fornitori/1/forniture/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/fornitori/{fornitore}/forniture/{fornitura}`

`PATCH api/v1/fornitori/{fornitore}/forniture/{fornitura}`


<!-- END_1aa5475ec60a9dd66087785b287a7903 -->

<!-- START_bf25a872e2a52fa42759da26b481fee6 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/fornitori/1/forniture/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/fornitori/{fornitore}/forniture/{fornitura}`


<!-- END_bf25a872e2a52fa42759da26b481fee6 -->

<!-- START_1bd3d3fe450077913f5a2e43ce9bd1bb -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X PATCH \
    "{{URL}}/api/v1/fornitori/1/forniture/1/restore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture/1/restore"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PATCH",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PATCH api/v1/fornitori/{fornitore}/forniture/{fornitura}/restore`


<!-- END_1bd3d3fe450077913f5a2e43ce9bd1bb -->

<!-- START_ad6a451db7936762d66c3eca0fe9f137 -->
## api/v1/fornitori/{fornitore}/forniture/{fornitura}/tariffe
> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/fornitori/1/forniture/1/tariffe" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture/1/tariffe"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/fornitori/{fornitore}/forniture/{fornitura}/tariffe`


<!-- END_ad6a451db7936762d66c3eca0fe9f137 -->

<!-- START_f3b1772fe7987d49e9e16887001271fc -->
## api/v1/fornitori/{fornitore}/forniture/{fornitura}/tariffe/{tariffa}
> Example request:

```bash
curl -X PATCH \
    "{{URL}}/api/v1/fornitori/1/forniture/1/tariffe/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture/1/tariffe/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PATCH",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PATCH api/v1/fornitori/{fornitore}/forniture/{fornitura}/tariffe/{tariffa}`


<!-- END_f3b1772fe7987d49e9e16887001271fc -->

<!-- START_7d31dfc4569214bd174f2f76a1e9a6d4 -->
## api/v1/fornitori/{fornitore}/forniture/{fornitura}/tariffe/{tariffa}
> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/fornitori/1/forniture/1/tariffe/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/fornitori/1/forniture/1/tariffe/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/fornitori/{fornitore}/forniture/{fornitura}/tariffe/{tariffa}`


<!-- END_7d31dfc4569214bd174f2f76a1e9a6d4 -->

#Fornitura


<!-- START_7265061ad585f4ce596fcb082566114c -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/forniture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/forniture"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/forniture`


<!-- END_7265061ad585f4ce596fcb082566114c -->

#Ordini


<!-- START_37a3f86e94fabcf203cb2e87e6d0f9a3 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/ordini" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/ordini`


<!-- END_37a3f86e94fabcf203cb2e87e6d0f9a3 -->

<!-- START_8f315e66c220959a5e49e5566bf2dd4d -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/ordini" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/ordini`


<!-- END_8f315e66c220959a5e49e5566bf2dd4d -->

<!-- START_9dc9a4eb9cc1bd73bde9178f7e2e9de5 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/ordini/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/ordini/{ordine}`


<!-- END_9dc9a4eb9cc1bd73bde9178f7e2e9de5 -->

<!-- START_f268c567e114e2c0a3d058be3e7b3580 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/ordini/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/ordini/{ordine}`

`PATCH api/v1/ordini/{ordine}`


<!-- END_f268c567e114e2c0a3d058be3e7b3580 -->

<!-- START_b4327742f6afce94937e2624f2fdc613 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/ordini/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/ordini/{ordine}`


<!-- END_b4327742f6afce94937e2624f2fdc613 -->

<!-- START_b947170aad2b21a9d2531ea3c17b563b -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/ordini/1/transazioni/paypal" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1/transazioni/paypal"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/ordini/{ordine}/transazioni/paypal`


<!-- END_b947170aad2b21a9d2531ea3c17b563b -->

#Ordini - Voci


<!-- START_69295e272f036458a9e35bd271e477fb -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/ordini/1/voci" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1/voci"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/ordini/{ordine}/voci`


<!-- END_69295e272f036458a9e35bd271e477fb -->

<!-- START_4e937e1b041aca3d7c53f7d84dd8d6c1 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/ordini/1/voci" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1/voci"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/ordini/{ordine}/voci`


<!-- END_4e937e1b041aca3d7c53f7d84dd8d6c1 -->

<!-- START_f1e3ea6e89adbe587ad661e2dc0f3056 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/ordini/1/voci/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1/voci/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/ordini/{ordine}/voci/{voce}`


<!-- END_f1e3ea6e89adbe587ad661e2dc0f3056 -->

<!-- START_9ff079d1edf05dfde809c4c449a6527e -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/ordini/1/voci/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1/voci/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/ordini/{ordine}/voci/{voce}`

`PATCH api/v1/ordini/{ordine}/voci/{voce}`


<!-- END_9ff079d1edf05dfde809c4c449a6527e -->

<!-- START_f0c7819f5c9b89764e650cf0a4cf1bca -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/ordini/1/voci/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/ordini/1/voci/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/ordini/{ordine}/voci/{voce}`


<!-- END_f0c7819f5c9b89764e650cf0a4cf1bca -->

#Settings


<!-- START_0f7c405a059a084f42490f2decb1584b -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/settings" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/settings"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/settings`


<!-- END_0f7c405a059a084f42490f2decb1584b -->

<!-- START_3756edec6e45a4253a6dd160792fc937 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/settings" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/settings"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/settings`


<!-- END_3756edec6e45a4253a6dd160792fc937 -->

<!-- START_ce3a9f778790f589ff9f377339f3b779 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/settings/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/settings/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/settings/{setting}`


<!-- END_ce3a9f778790f589ff9f377339f3b779 -->

<!-- START_550714992130b95f5af084c3f0039b70 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/settings/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/settings/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/settings/{setting}`

`PATCH api/v1/settings/{setting}`


<!-- END_550714992130b95f5af084c3f0039b70 -->

<!-- START_f68a0ce86a556742c7b909fbc30b95f5 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/settings/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/settings/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/settings/{setting}`


<!-- END_f68a0ce86a556742c7b909fbc30b95f5 -->

#Users


<!-- START_7606a0e497ca7585e47db29f81ca0f5a -->
## api/v1/account
> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/account" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/account"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/account`


<!-- END_7606a0e497ca7585e47db29f81ca0f5a -->

<!-- START_1aff981da377ba9a1bbc56ff8efaec0d -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/users`


<!-- END_1aff981da377ba9a1bbc56ff8efaec0d -->

<!-- START_4194ceb9a20b7f80b61d14d44df366b4 -->
## Salva un nuovo utente nel database.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/users`


<!-- END_4194ceb9a20b7f80b61d14d44df366b4 -->

<!-- START_cedc85e856362e0e3b46f5dcd9f8f5d0 -->
## Mostra un utente specifico.

> Example request:

```bash
curl -X GET \
    -G "{{URL}}/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/v1/users/{user}`


<!-- END_cedc85e856362e0e3b46f5dcd9f8f5d0 -->

<!-- START_296fac4bf818c99f6dd42a4a0eb56b58 -->
## Aggiorna i dati di un utente nel database.

> Example request:

```bash
curl -X PUT \
    "{{URL}}/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/users/{user}`

`PATCH api/v1/users/{user}`


<!-- END_296fac4bf818c99f6dd42a4a0eb56b58 -->

<!-- START_22354fc95c42d81a744eece68f5b9b9a -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "{{URL}}/api/v1/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/users/{user}`


<!-- END_22354fc95c42d81a744eece68f5b9b9a -->

#general


<!-- START_1d9776bb937d0315065d2847f9ab4cf6 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "{{URL}}/api/v1/webhooks/paypal" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer {{token}}"
```

```javascript
const url = new URL(
    "{{URL}}/api/v1/webhooks/paypal"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer {{token}}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/webhooks/paypal`


<!-- END_1d9776bb937d0315065d2847f9ab4cf6 -->


