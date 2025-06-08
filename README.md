# Superconductor Streamable HTTP

**HTTP transport layer for Superconductor MCP servers - fully functional and ready to use.**

---

## What This Package Does

This package implements the StreamableHTTP transport layer** that plugs into Superconductor Core via the Protocol Manager. It exposes your MCP server over HTTP endpoints, with all MCP protocol processing handled seamlessly by the Superconductor Core middleware pipeline.

**Status:** ✅ **Production Ready** - Complete HTTP transport implementation with middleware-based processing.

## Features

### ✅ Complete HTTP Transport
- **POST `/mcp`** - Fully functional message endpoint for all MCP communication
- **GET `/mcp`** - SSE stream endpoint (when enabled)
- **Protocol Manager Integration** - Automatically registered as a transport protocol
- **Middleware Pipeline** - All MCP processing handled by Superconductor Core middleware
- **Session Management** - Automatic session creation and loading via middleware

### ✅ Laravel Integration
- **Auto-Registration** - Service provider automatically registers routes and config
- **Config Merging** - Configuration seamlessly merges with Superconductor Core config
- **Zero Configuration** - Works immediately after installation
- **Middleware Support** - Uses standard Laravel middleware patterns

### 🔧 Configurable Options
- **SSE Toggle** - Enable/disable Server-Sent Events streaming
- **Route Customization** - Integrates with Superconductor Core's routing configuration
- **Middleware Stack** - Configurable via Superconductor Core's middleware system

## Installation

```bash
composer require superconductor-mcp/streamable-http
```

**That's it!** Your Laravel app now has working MCP HTTP endpoints:

- **`POST /mcp`** - Ready for any MCP client
- **`GET /mcp`** - SSE streaming (when enabled)

## How It Works

This package integrates with Superconductor Core through the **Protocol Manager**:

1. **Auto-Registration** - Service provider registers the transport protocol
2. **Config Merging** - Package config merges into `config('mcp.protocols.streamable-http')`
3. **Route Registration** - HTTP routes use Superconductor Core's middleware pipeline
4. **Request Processing** - All MCP logic handled by Core middleware, transport just provides HTTP access

### Architecture

```
HTTP Request → Laravel Route → Middleware Pipeline → MCP Core → Response
                                     ↑
                            (CreateOrLoadMCPSession, 
                             protocol handlers,
                             method dispatching)
```

The controller delegates to middleware - this is **intentional design**, not a limitation.

## Configuration

The package includes focused configuration for HTTP transport:

```php
// config/mcp/protocols/streamable-http.php
return [
    'streamable_sse_enabled' => false,
];
```

### Enable SSE Streaming

```php
// Enable Server-Sent Events
'streamable_sse_enabled' => true,
```

### Advanced Configuration

For routing, middleware, authentication, and other options, use Superconductor Core's configuration:

```php
// config/mcp.php - handled by Superconductor Core
'endpoints' => [
    'middleware' => [
        'starting' => ['api', 'auth:sanctum'], // Applied to all endpoints
        'message' => ['throttle:60,1'],        // Applied to POST /mcp
    ],
],
```

## Client Integration

### Test with cURL

```bash
# List capabilities
curl -X POST http://your-app.test/mcp \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","id":1,"method":"capabilities/list"}'

# Call a tool
curl -X POST http://your-app.test/mcp \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","id":1,"method":"tools/call","params":{"name":"your_tool","arguments":{}}}'
```

### Claude Desktop

```json
{
  "mcpServers": {
    "my-laravel-app": {
      "url": "https://your-app.com/mcp"
    }
  }
}
```

### MCP Inspector

```bash
npx @modelcontextprotocol/inspector
# Connect to: http://your-app.test/mcp
```

## SSE Implementation Status

- **POST endpoint** - ✅ Fully functional
- **GET endpoint (SSE)** - 🚧 Infrastructure ready, streaming logic pending

Most MCP clients only need the POST endpoint. SSE is for advanced server-to-client notifications.

## Integration with Superconductor Core

This package works as a **transport layer** that integrates with Superconductor Core's Protocol Manager:

- **Protocol Registration** - Automatically registers as `streamable-http` protocol
- **Config Integration** - Merges into `mcp.protocols.streamable-http` config tree
- **Middleware Delegation** - All MCP processing handled by Core's middleware pipeline
- **Session Management** - Uses Core's `CreateOrLoadMCPSession` middleware
- **Method Dispatching** - Core handles all JSON-RPC method routing and execution

## Requirements

- `projectsaturnstudios/superconductor-core` ^0.2.0
- Laravel 12+ (earlier versions may work)
- PHP 8.2+

## Development

The package follows the **delegation pattern** - StreamableHTTP transport concerns are separated from MCP protocol concerns:

```php
// StreamableHttpEndpointsController
public function message(): Response
{
    // Middleware pipeline handles:
    // - Session creation/loading
    // - JSON-RPC parsing
    // - Method dispatching
    // - Response formatting
    return response()->noContent(202);
}
```

This architecture allows the transport layer to focus purely on HTTP concerns while Core handles all MCP protocol logic.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

**Connect your Laravel MCP server to any StreamableHTTP-enabled AI client! 🚀** 
