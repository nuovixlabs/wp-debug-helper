# 🐞 WP Debug Helper

## 🤨 What the hell is it?

WP Debug Helper is a lightweight WordPress plugin that simplifies debugging through WP-CLI commands. It provides an easy way to enable/disable debugging, test logging functionality, and view log files - all from the command line! Perfect for local development and troubleshooting WordPress issues without modifying configuration files manually.

## ⚙️ Installation

1. Download or clone this repository
2. Place the plugin file in your WordPress plugins directory (`wp-content/plugins/`)
3. Activate the plugin via WP-CLI:
   ```bash
   wp plugin activate wp-debug-helper
   ```

## 🚀 How to Use

All commands use the `wp debug` namespace. Simply run them from your terminal in your WordPress directory.

## 📋 Available Commands

### Enable debugging

```bash
wp debug enable
```

Enables WordPress debugging with logging (but without on-screen display).

**Options:**

- `--display`: Also enable on-screen error display (not recommended for production)

### Disable debugging

```bash
wp debug disable
```

Disables all WordPress debugging functionality.

### Check debug status

```bash
wp debug status
```

Shows current debugging configuration status and log file information.

### Test debug logging

```bash
wp debug test
```

Generates a test log entry to verify that logging is working correctly.

### View debug log

```bash
wp debug view
```

Displays the contents of the debug log file.

**Options:**

- `--tail=<lines>`: Show only the last specified number of lines
- `--clear`: Clear the log file after viewing

## 💡 Examples

```bash
# Enable debugging and check its status
wp debug enable
wp debug status

# Test if logging works
wp debug test

# View only the last 10 lines of the log
wp debug view --tail=10

# View the entire log and then clear it
wp debug view --clear

# Disable debugging when finished
wp debug disable
```

## 🤔 Why Use This Plugin?

- 🔄 Toggle debugging without editing wp-config.php
- 🔍 Quick status checks to verify configuration
- 📝 Easy log viewing and management
- ⏱️ Saves development time
- 🛡️ Helps identify and fix WordPress issues

---

## Copyright Information 📝

Crafted with ♥️ by [Rakesh Mandal](https://github.com/therakeshm) at [nuovixlabs](https://github.com/nuovixlabs)

---

**License:** GPL v2 or later
