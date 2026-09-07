# Development Guide

## Frontend Development

### Vue Components Structure

Setiap page component harus mengikuti struktur:

```vue
<template>
  <div>
    <!-- HTML template -->
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  setup() {
    // State
    const data = ref()
    
    // Methods
    const fetchData = async () => {
      // API call
    }
    
    // Lifecycle
    onMounted(() => {
      fetchData()
    })
    
    return {
      data,
      fetchData
    }
  }
}
</script>
```

### Axios Configuration

Axios sudah dikonfigurasi di `app.js` dengan:
- Base URL: `/api`
- Authorization header otomatis dari token di localStorage

### Adding New Routes

1. Create component di `resources/js/pages/`
2. Add route di `resources/js/router/index.js`:

```javascript
{
  path: '/new-page',
  name: 'NewPage',
  component: NewPage,
  meta: { requiresAuth: true, roles: ['admin_portal'] }
}
```

3. Update navigation di `App.vue`

### Styling

Proyek menggunakan Tailwind CSS. Customize di `tailwind.config.js`.

## Backend Development

### Creating New Endpoint

1. Create controller:
```bash
php artisan make:controller Api/NewController
```

2. Add method:
```php
public function index()
{
    return response()->json([...]);
}
```

3. Add route di `routes/api.php`:
```php
Route::get('/new-endpoint', [NewController::class, 'index']);
```

### Creating New Model

```bash
php artisan make:model NewModel -m
```

ini akan membuat model dan migration file.

### Creating New Migration

```bash
php artisan make:migration create_new_table
```

Edit file migration dan jalankan:
```bash
php artisan migrate
```

## Testing

### API Testing dengan Postman

1. Import collection (will be added)
2. Set `{{token}}` variable setelah login
3. Test semua endpoints

### Unit Testing

```bash
php artisan test
```

## Git Workflow

```bash
# Create feature branch
git checkout -b feature/feature-name

# Make changes
git add .
git commit -m "Add: feature description"

# Push to remote
git push origin feature/feature-name

# Create Pull Request
```

## Database Schema

### Users Table
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('viewer', 'admin_opd', 'admin_portal') DEFAULT 'viewer',
  opd_id BIGINT,
  is_active BOOLEAN DEFAULT 0,
  email_verified_at TIMESTAMP NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (opd_id) REFERENCES opds(id)
);
```

### Datasets Table
```sql
CREATE TABLE datasets (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(100) NOT NULL,
  user_id BIGINT NOT NULL,
  opd_id BIGINT NOT NULL,
  is_public BOOLEAN DEFAULT 0,
  row_count INT DEFAULT 0,
  file_size INT DEFAULT 0,
  published_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (opd_id) REFERENCES opds(id)
);
```

## Performance Tips

1. **Database:**
   - Add indexes untuk frequently queried columns
   - Use eager loading dengan `with()`
   - Pagination untuk large datasets

2. **Frontend:**
   - Lazy load components
   - Optimize images
   - Cache API responses jika perlu

3. **Backend:**
   - Cache query results
   - Use queue untuk heavy operations
   - Optimize N+1 queries

## Security Checklist

- [ ] Validate all user inputs
- [ ] Use HTTPS di production
- [ ] Sanitize file uploads
- [ ] Implement CSRF protection
- [ ] Use environment variables untuk sensitive data
- [ ] Regular security audits
- [ ] Keep dependencies updated

## Common Issues & Solutions

### Issue: CORS Error
**Solution:** Add domain ke CORS config atau use proxy

### Issue: Token Expired
**Solution:** Implement token refresh logic

### Issue: File Upload Size Limit
**Solution:** Update `php.ini`:
```ini
upload_max_filesize = 100M
post_max_size = 100M
```
