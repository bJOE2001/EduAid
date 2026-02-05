# Boot Files

Boot files are files that run before your app is mounted. They are used to configure libraries, initialize services, etc.

## Current Boot Files

- `axios.js` - Configures Axios for API calls with authentication interceptors

## Adding New Boot Files

1. Create a new file in this directory
2. Export a default function that receives the app instance
3. The file will be automatically loaded by Quasar
