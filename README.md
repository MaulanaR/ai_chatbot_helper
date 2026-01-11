# Laravel + Inertia.js Chatbot SaaS

A complete Chatbot management SaaS platform built with Laravel 11, Inertia.js, Vue.js 3, and Tailwind CSS. Users can create custom chatbots, upload knowledge base content (PDF/Text), and generate embed codes for their websites.

## Features

- 🤖 **Custom Chatbot Creation**: Create multiple chatbots with custom names and system prompts
- 📚 **Knowledge Base Management**: Upload PDF files or add text content as knowledge base
- 🔗 **Embed Widget**: Generate iframe embed codes to display chatbots on any website
- 🎨 **Modern UI**: Beautiful responsive interface built with Vue.js 3 and Tailwind CSS
- ⚡ **Real-time Chat**: Interactive chat interface with typing indicators
- 🔒 **Authentication**: Secure user authentication with Laravel's built-in auth system
- 📱 **Responsive**: Mobile-friendly chat widget design

## Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Vue.js 3 (Composition API) with Inertia.js
- **Styling**: Tailwind CSS
- **Database**: MySQL/SQLite
- **PDF Processing**: Smalot PDF Parser
- **HTTP Client**: Guzzle HTTP
- **LLM Integration**: Custom service for localhost:8080 LLM engine

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ChatbotController.php       # CRUD Bot (Admin Side)
│   │   └── Public/
│   │       └── ChatWidgetController.php # Public Widget logic
│   └── Requests/
├── Models/
│   ├── Chatbot.php                    # Chatbot model with relationships
│   ├── KnowledgeBase.php               # Knowledge base model
│   └── User.php                      # User model
├── Services/
│   ├── LlmEngineService.php           # Handle HTTP to localhost:8080
│   └── PdfParserService.php           # Handle PDF to text conversion
├── Policies/
│   └── ChatbotPolicy.php             # Authorization policies

resources/js/
├── Layouts/
│   ├── AuthenticatedLayout.vue        # Dashboard Layout
│   └── WidgetLayout.vue             # Clean Layout for Iframe
├── Pages/
│   ├── Chatbots/
│   │   ├── Index.vue                # List user's bots
│   │   ├── Create.vue               # Form upload PDF/add text
│   │   └── Show.vue                # Bot details & embed code
│   └── Widget/
│       └── ChatWindow.vue           # UI Chat Bubble
```

## Installation

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL or SQLite
- LLM Engine running on localhost:8080

### Quick Setup

1. **Install dependencies**:
```bash
composer install
npm install
```

2. **Environment setup**:
```bash
# Edit .env yang sudah ada atau buat baru
php artisan key:generate
```

3. **Database configuration**:
Untuk development dengan SQLite (recommended):
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Untuk production dengan MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. **Create database file (untuk SQLite)**:
```bash
# Di Windows:
New-Item -Path "database" -Name "database.sqlite" -ItemType File

# Di Linux/Mac:
touch database/database.sqlite
```

5. **Run migrations**:
```bash
php artisan migrate
```

6. **Link storage**:
```bash
php artisan storage:link
```

7. **Compile frontend assets**:
```bash
npm run dev
```

8. **Start application**:
```bash
php artisan serve
```

Aplikasi akan berjalan di http://localhost:8000

3. **Database configuration**:
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat_bot_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. **Run migrations**:
```bash
php artisan migrate
```

5. **Link storage**:
```bash
php artisan storage:link
```

6. **Compile frontend assets**:
```bash
npm run dev
```

7. **Start the application**:
```bash
php artisan serve
```

## LLM Engine Integration

The application expects an LLM engine running at `http://localhost:8080`. The engine should support the following API:

```http
POST /v1/chat/completions
Content-Type: application/json

{
  "model": "gpt-3.5-turbo",
  "messages": [
    {
      "role": "user",
      "content": "Your prompt here"
    }
  ],
  "temperature": 0.7,
  "max_tokens": 1000
}
```

## API Endpoints

### Authentication Routes
- `GET /login` - Login page
- `GET /register` - Registration page

### User Routes (Authenticated)
- `GET /dashboard` - User dashboard
- `GET /chatbots` - List user's chatbots
- `GET /chatbots/create` - Create new chatbot form
- `POST /chatbots` - Store new chatbot
- `GET /chatbots/{id}` - Show chatbot details
- `GET /chatbots/{id}/edit` - Edit chatbot form
- `PUT /chatbots/{id}` - Update chatbot
- `DELETE /chatbots/{id}` - Delete chatbot

### Public Widget Routes
- `GET /widget/{uuid}` - Display chat widget
- `POST /widget/{uuid}/send` - Send message to chatbot
- `GET /widget/{uuid}/info` - Get chatbot info

## Usage

### Creating a Chatbot

1. Login to your dashboard
2. Navigate to "My Chatbots"
3. Click "Create New Chatbot"
4. Fill in chatbot name and optional system prompt
5. Choose knowledge base type:
   - **Text**: Direct text input
   - **PDF**: Upload PDF file (will be extracted to text)
6. Click "Create Chatbot"

### Embedding Chatbot

1. View your chatbot details
2. Copy the HTML embed code
3. Paste it into your website HTML:

```html
<iframe src="https://yourdomain.com/widget/{uuid}" width="400" height="600"></iframe>
```

### Widget Customization

The chat widget automatically adapts to different screen sizes and includes:
- Responsive chat bubbles
- Typing indicators
- Message timestamps
- Scroll-to-bottom for new messages

## Configuration

### LLM Engine Configuration

Add these to your `.env` file:
```env
LLM_ENGINE_URL=http://localhost:8080
LLM_ENGINE_TIMEOUT=60
```

### File Upload Limits

PDF uploads are limited to 10MB. Adjust in `ChatbotController.php` validation rules.

## Development

### Running Development Server

```bash
# Backend server
php artisan serve

# Frontend development server
npm run dev
```

### Building for Production

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Security Features

- User authentication and authorization
- File upload validation
- CSRF protection
- SQL injection prevention
- XSS protection
- Rate limiting considerations

## Database Schema

### Chatbots Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `name` - Chatbot name
- `uuid` - Unique identifier for public access
- `system_prompt` - Default system prompt
- `created_at`, `updated_at` - Timestamps

### Knowledge Bases Table
- `id` - Primary key
- `chatbot_id` - Foreign key to chatbots
- `type` - Enum: 'text' or 'pdf'
- `file_path` - Path to uploaded PDF file
- `content` - Extracted text content
- `created_at`, `updated_at` - Timestamps

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is open-sourced software licensed under the MIT license.

## Support

For support, please create an issue in the GitHub repository or contact the development team.
