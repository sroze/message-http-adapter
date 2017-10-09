# HTTP adapter for Symfony's Message component

Messages can also come from HTTP messages or go to other APIs through HTTP requests. This adapter will help you doing so
in a very easy manner with the Symfony Message component.

## Usage

They are two scenarios:
- [Receive HTTP messages](#receive-http-messages)
- [Send HTTP messages](#send-http-messages)

### Receive HTTP messages

1. Configure the adapter to receive your messages

```yaml
# config/packages/message_http_adapter.yaml
message_http_adapter:
    consumers:
        - path: '/api/do-something'
          message: 'App\Message\DoSomething'
```

2. Configure Symfony's router to use your HTTP consumers
```yaml
# app/config/routing.yml
http_messages:
    resource: .
    type: http_messages
```

3. Send your HTTP request!
```
curl 'http://localhost:8000/api/do-something' \
    -X POST \
    --data-binary '{"propertyOfDoSomethingObject": "yourValue"}' --compressed
```

### Send HTTP messages

1. Configure the producer

```yaml
# config/packages/message_http_adapter.yaml
message_http_adapter:
    producers:
        requestbin:
            endpoint: 'https://requestb.in/pdjzjmpd'
```

2. Route messages to the HTTP adapter producers

```yaml
# config/packages/
framework:
    message:
        routing:
            'App\Message\Send3rdPartyNotification': message_http_adapter.producer.requestbin
```
