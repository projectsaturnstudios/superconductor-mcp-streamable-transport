1. To spin up a client:
```php
use Superconductor\Transports\StreamableHttp\Support\Facades\StreamableHttp;
use Superconductor\Transports\StreamableHttp\DTO\Servers\StreamableServerConfig;

// Define the command to run the MCP server
$mcp_server_config = [
    "url" => "https://api.githubcopilot.com/mcp/",
    "headers" => [
        "Authorization" => "auth_token"
    ],
];

$mcp_server = new StreamableServerConfig(...$mcp_server_config)

// Start the client by passing in the command configuration
$client = StreamableHttp::client($mcp_server)

```

