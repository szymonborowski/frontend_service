# Xdebug Configuration for Cursor IDE - Frontend Microservice

Xdebug 3.5.0 jest zainstalowany i skonfigurowany dla mikrousługi frontend.

## Aktualna Konfiguracja

**Ustawienia Xdebug:**
- Mode: `debug`, `develop`, `coverage`
- Client Host: `host.docker.internal`
- Client Port: `9003`
- IDE Key: `PHPSTORM`
- Start with request: `yes`

## Konfiguracja Cursor IDE

### 1. Zainstaluj rozszerzenie PHP Debug

1. Otwórz Cursor IDE
2. Przejdź do Extensions (Ctrl+Shift+X)
3. Wyszukaj i zainstaluj: **PHP Debug** by Felix Becker
4. Zrestartuj Cursor IDE

### 2. Utwórz konfigurację launch.json

1. W Cursor IDE, kliknij ikona Run and Debug (Ctrl+Shift+D)
2. Kliknij **create a launch.json file**
3. Wybierz **PHP**
4. Zastąp zawartość pliku następującą konfiguracją:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}/src"
            },
            "log": true,
            "xdebugSettings": {
                "max_data": 65535,
                "show_hidden": 1,
                "max_children": 100,
                "max_depth": 5
            }
        },
        {
            "name": "Launch currently open script",
            "type": "php",
            "request": "launch",
            "program": "${file}",
            "cwd": "${fileDirname}",
            "port": 9003,
            "runtimeArgs": [
                "-dxdebug.mode=debug",
                "-dxdebug.start_with_request=yes"
            ],
            "env": {
                "XDEBUG_MODE": "debug,develop",
                "XDEBUG_CONFIG": "client_host=host.docker.internal"
            }
        }
    ]
}
```

Plik zostanie zapisany w `.vscode/launch.json` w katalogu workspace.

### 3. Alternatywnie - ręczne utworzenie pliku

Jeśli automatyczna konfiguracja nie działa, utwórz plik ręcznie:

```bash
mkdir -p /home/decybell/dev/portfolio/frontend/.vscode
```

Następnie utwórz plik `/home/decybell/dev/portfolio/frontend/.vscode/launch.json` z konfiguracją powyżej.

### 4. Konfiguracja settings.json (opcjonalnie)

Utwórz lub edytuj `.vscode/settings.json`:

```json
{
    "php.validate.executablePath": "/usr/bin/php",
    "php.debug.executablePath": "/usr/bin/php"
}
```

## Testowanie Xdebug

### Metoda 1: Debug przez przeglądarkę

1. **Zainstaluj rozszerzenie przeglądarki:**
   - Chrome: [Xdebug Helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
   - Firefox: [Xdebug Helper](https://addons.mozilla.org/en-US/firefox/addon/xdebug-helper-for-firefox/)

2. **Uruchom debugger w Cursor:**
   - Przejdź do widoku Run and Debug (Ctrl+Shift+D)
   - Wybierz konfigurację **"Listen for Xdebug"**
   - Kliknij zieloną strzałkę (Start Debugging) lub naciśnij F5
   - W dolnej części edytora zobaczysz pasek debug (pomarańczowy)

3. **Ustaw breakpoint:**
   - Otwórz dowolny controller (np. `src/app/Http/Controllers/MeController.php`)
   - Kliknij na lewy margines linii kodu (pojawi się czerwona kropka)

4. **Testuj:**
   - W przeglądarce, włącz Xdebug Helper (zielona ikona)
   - Odwiedź https://portfolio.microservices.local
   - Cursor powinien zatrzymać się na breakpoincie!

### Metoda 2: Parametr URL (bez rozszerzenia)

Dodaj `XDEBUG_SESSION_START=1` do URL:
```
https://portfolio.microservices.local/?XDEBUG_SESSION_START=1
```

### Metoda 3: Debug poleceń Artisan

Debug komendy artisan wewnątrz kontenera:

1. W Cursor, ustaw breakpoint w kodzie komendy
2. Uruchom debugger (F5)
3. W terminalu wykonaj:

```bash
docker exec -it frontend-app bash
php -dxdebug.mode=debug artisan your:command
```

## Weryfikacja Konfiguracji

### Sprawdź czy Xdebug jest załadowany:

```bash
docker exec frontend-app php -v
```

Oczekiwany output:
```
PHP 8.5.2 (cli) (built: Feb  3 2026 02:27:39) (NTS)
    with Xdebug v3.5.0, Copyright (c) 2002-2025, by Derick Rethans
```

### Sprawdź konfigurację Xdebug:

```bash
docker exec frontend-app php -r "echo 'Xdebug Mode: ' . ini_get('xdebug.mode') . PHP_EOL; echo 'Client Host: ' . ini_get('xdebug.client_host') . PHP_EOL; echo 'Client Port: ' . ini_get('xdebug.client_port') . PHP_EOL;"
```

Oczekiwany output:
```
Xdebug Mode: debug,develop,coverage
Client Host: host.docker.internal
Client Port: 9003
```

## Troubleshooting

### Problem: Breakpointy nie działają

1. **Sprawdź czy debugger nasłuchuje:**
   - W Cursor, status bar (dolna część) powinien być pomarańczowy podczas debugowania
   - Sprawdź OUTPUT → "PHP Debug" w Cursor

2. **Sprawdź path mappings:**
   - W `.vscode/launch.json` upewnij się że:
     - `/var/www/html` mapuje się na `${workspaceFolder}/src`
   - Workspace folder to: `/home/decybell/dev/portfolio/frontend`

3. **Sprawdź logi Xdebug:**
   ```bash
   docker exec frontend-app cat /tmp/xdebug.log
   ```

4. **Włącz debug log w Cursor:**
   - OUTPUT panel → wybierz "PHP Debug" z dropdown
   - Zobaczysz szczegółowe logi połączenia

### Problem: "Connection refused"

1. **Sprawdź czy host.docker.internal działa:**
   ```bash
   docker exec frontend-app ping -c 2 host.docker.internal
   ```

2. **Sprawdź firewall:**
   - Upewnij się że port 9003 nie jest zablokowany:
   ```bash
   sudo ufw status
   sudo ufw allow 9003/tcp
   ```

3. **Sprawdź czy Cursor nasłuchuje na poprawnym porcie:**
   - W `.vscode/launch.json` port powinien być `9003`

### Problem: Breakpoint jest szary (nie aktywny)

1. Path mapping jest nieprawidłowy
2. Kod w kontenerze różni się od kodu lokalnego
3. Upewnij się że używasz volumenu: `./src:/var/www/html`

### Problem: Wolne działanie aplikacji

Jeśli Xdebug spowalnia aplikację gdy nie debugujesz:

**Opcja 1:** Zatrzymaj debugger w Cursor (Shift+F5)

**Opcja 2:** Tymczasowo wyłącz Xdebug:
```bash
# Skomentuj volume z xdebug.ini w docker-compose.yml
docker-compose restart frontend-app
```

## Debug w Praktyce

### Przykład 1: Debug request w kontrolerze

```php
// src/app/Http/Controllers/MeController.php

public function show(Request $request)
{
    // Ustaw breakpoint tutaj (kliknij na marginesie)
    $user = Auth::user();

    // Gdy aplikacja się zatrzyma, możesz:
    // - Sprawdzić wartości zmiennych (hover)
    // - Wykonać kod w Debug Console
    // - Przejść krok po kroku (F10)

    return response()->json($user);
}
```

### Przykład 2: Debug API call

```php
// src/app/Services/BlogApiService.php

public function getRecentPosts(int $limit = 10): array
{
    // Breakpoint tutaj
    $response = $this->http()->get("{$this->baseUrl}/posts", [
        'per_page' => $limit,
        'status' => 'published',
    ]);

    // Sprawdź co zwraca API
    if ($response->successful()) {
        return $response->json('data') ?? [];
    }

    return [];
}
```

### Używanie Debug Console

Podczas gdy debugger jest zatrzymany na breakpoincie, możesz wykonać kod PHP w Debug Console (Ctrl+Shift+Y):

```php
// Sprawdź zmienne
$user->name

// Wywołaj metody
$this->getRecentPosts(5)

// Sprawdź konfigurację
config('services.blog.url')
```

## Skróty Klawiszowe

- **F5** - Start debugging / Continue
- **F9** - Toggle breakpoint
- **F10** - Step over
- **F11** - Step into
- **Shift+F11** - Step out
- **Shift+F5** - Stop debugging
- **Ctrl+Shift+D** - Otwórz panel Debug

## Dodatkowe Funkcje

### Conditional Breakpoints

1. Kliknij prawym przyciskiem myszy na breakpoincie
2. Wybierz "Edit Breakpoint"
3. Dodaj warunek, np.: `$user->id === 1`

### Logpoints

1. Kliknij prawym przyciskiem myszy na marginesie
2. Wybierz "Add Logpoint"
3. Wpisz wyrażenie, np.: `User ID: {$user->id}`
4. Nie zatrzymuje wykonania, tylko loguje do Debug Console

## Struktura Projektu

```
frontend/
├── .vscode/
│   └── launch.json          # Konfiguracja debug
├── docker/
│   └── dev/
│       └── php/
│           ├── Dockerfile
│           └── xdebug.ini   # Konfiguracja Xdebug
├── docker-compose.yml        # Xdebug env variables
└── src/                      # Twój kod PHP (mapowany do /var/www/html)
```

## Przydatne Komendy

```bash
# Restart kontenera z Xdebug
cd /home/decybell/dev/portfolio/frontend
docker-compose restart frontend-app

# Zobacz logi PHP-FPM
docker-compose logs -f frontend-app

# Wejdź do kontenera
docker exec -it frontend-app bash

# Test Xdebug
docker exec frontend-app php -dxdebug.mode=debug -r "echo 'Test';"

# Zobacz pełną konfigurację PHP
docker exec frontend-app php -i | grep xdebug
```

## Dodatkowe Zasoby

- [VS Code PHP Debug Extension](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug)
- [Xdebug 3 Documentation](https://xdebug.org/docs)
- [Cursor IDE Documentation](https://cursor.sh/docs)

## Quick Start Checklist

- [ ] Zainstalowane rozszerzenie "PHP Debug" w Cursor
- [ ] Utworzony plik `.vscode/launch.json`
- [ ] Kontener frontend-app jest uruchomiony
- [ ] Debugger nasłuchuje (F5 w Cursor)
- [ ] Ustawiony breakpoint w kontrolerze
- [ ] Xdebug Helper włączony w przeglądarce
- [ ] Odwiedzono stronę - Cursor zatrzymał się na breakpoincie ✓

---

**Konfiguracja Xdebug:**
```ini
File: docker/dev/php/xdebug.ini
Port: 9003
Mode: debug,develop,coverage
Host: host.docker.internal
```

Gotowe do debugowania! 🐛
