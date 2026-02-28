# Xdebug Configuration for Frontend Microservice

Xdebug 3.5.0 is now installed and configured for the frontend microservice.

## Current Configuration

**Xdebug Settings:**
- Mode: `debug`, `develop`, `coverage`
- Client Host: `host.docker.internal`
- Client Port: `9003`
- IDE Key: `PHPSTORM`
- Start with request: `yes`

## PHPStorm/IntelliJ IDEA Setup

### 1. Configure PHP Interpreter

1. Open **Settings** → **PHP**
2. Click the **...** button next to **CLI Interpreter**
3. Click **+** → **From Docker, Vagrant, VM, WSL, Remote...**
4. Select **Docker Compose**
5. Configuration:
   - Server: Docker
   - Configuration files: `./docker-compose.yml`
   - Service: `frontend-app`
   - Click **OK**

### 2. Configure Path Mappings

1. In **Settings** → **PHP**
2. Find your Docker interpreter
3. Click the folder icon next to **Docker container**
4. In **Volume bindings**:
   - Host path: `/home/decybell/dev/portfolio/frontend/src`
   - Container path: `/var/www/html`

### 3. Configure Debug Server

1. **Settings** → **PHP** → **Servers**
2. Click **+** to add a new server
3. Configuration:
   - Name: `frontend-docker` (must match `PHP_IDE_CONFIG` in docker-compose)
   - Host: `portfolio.microservices.local` (or your actual domain)
   - Port: `443`
   - Debugger: `Xdebug`
   - ✅ Check **Use path mappings**
   - Map: `/home/decybell/dev/portfolio/frontend/src` → `/var/www/html`

### 4. Configure Debug Settings

1. **Settings** → **PHP** → **Debug**
2. **Xdebug** section:
   - Debug port: `9003`
   - ✅ Check **Can accept external connections**
   - ✅ Check **Break at first line in PHP scripts** (for initial testing, disable later)
   - ✅ Check **Force break at first line when no path mapping specified**
   - ✅ Check **Force break at first line when a script is outside the project**

### 5. Start Listening for Debug Connections

1. Click the **phone icon** in the toolbar (Start Listening for PHP Debug Connections)
2. The icon should turn green when listening

## Testing Xdebug

### Method 1: Browser with Xdebug Helper

1. Install browser extension:
   - Chrome: [Xdebug Helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
   - Firefox: [Xdebug Helper](https://addons.mozilla.org/en-US/firefox/addon/xdebug-helper-for-firefox/)

2. Click the extension icon and select **Debug**
3. Set a breakpoint in PHPStorm (e.g., in a controller)
4. Access your application at https://portfolio.microservices.local
5. PHPStorm should break at your breakpoint

### Method 2: Manual URL Parameter

Add `XDEBUG_SESSION_START=PHPSTORM` to any URL:
```
https://portfolio.microservices.local/?XDEBUG_SESSION_START=PHPSTORM
```

### Method 3: Command Line Debugging (Artisan, Tests)

Debug artisan commands inside the container:

```bash
# SSH into container
docker exec -it frontend-app bash

# Run with Xdebug enabled
php -dxdebug.mode=debug artisan your:command
```

Debug PHPUnit tests:
```bash
# In PHPStorm, create a PHPUnit Run Configuration
# Settings → PHP → Test Frameworks → Add PHPUnit by Remote Interpreter
# Choose your Docker interpreter
# Then run tests with debug icon
```

## Verify Xdebug is Working

Run this command to check Xdebug status:
```bash
docker exec frontend-app php -v
```

Expected output should include:
```
with Xdebug v3.5.0, Copyright (c) 2002-2025, by Derick Rethans
```

Check Xdebug configuration:
```bash
docker exec frontend-app php -i | grep xdebug.mode
```

Expected output:
```
xdebug.mode => debug,develop,coverage => debug,develop,coverage
```

## Troubleshooting

### Breakpoints not working

1. **Verify PHPStorm is listening:**
   - Green phone icon in toolbar

2. **Check path mappings:**
   - Settings → PHP → Servers
   - Ensure `/home/decybell/dev/portfolio/frontend/src` maps to `/var/www/html`

3. **Check firewall:**
   - Ensure port 9003 is not blocked

4. **View Xdebug logs:**
   ```bash
   docker exec frontend-app cat /tmp/xdebug.log
   ```

### Connection refused

1. **Check host.docker.internal resolves:**
   ```bash
   docker exec frontend-app ping host.docker.internal
   ```

2. **Verify PHPStorm debug port:**
   - Settings → PHP → Debug → Xdebug → Debug port should be 9003

### Performance issues

If Xdebug slows down your application when not debugging:

1. **Option 1:** Stop listening in PHPStorm (click phone icon)
2. **Option 2:** Disable Xdebug temporarily:
   - Comment out the xdebug.ini volume mount in docker-compose.yml
   - Restart container: `docker-compose restart frontend-app`

## Additional Resources

- [Xdebug 3 Documentation](https://xdebug.org/docs)
- [PHPStorm Docker Debug Guide](https://www.jetbrains.com/help/phpstorm/docker.html)
- [Xdebug 3 Upgrade Guide](https://xdebug.org/docs/upgrade_guide)

## Quick Reference

**Xdebug Configuration File:**
```
/home/decybell/dev/portfolio/frontend/docker/dev/php/xdebug.ini
```

**Docker Compose Environment:**
```yaml
environment:
  - PHP_IDE_CONFIG=serverName=frontend-docker
  - XDEBUG_CONFIG=client_host=host.docker.internal
```

**Useful Commands:**
```bash
# Restart with fresh build
cd /home/decybell/dev/portfolio/frontend
docker-compose up -d --build frontend-app

# View PHP info
docker exec frontend-app php -i | grep xdebug

# View Xdebug log
docker exec frontend-app cat /tmp/xdebug.log

# Test Xdebug connectivity
docker exec frontend-app php -dxdebug.mode=debug -r "echo 'Testing Xdebug';"
```
