# Project Plan: Laravel + Inertia.js Chatbot SaaS

## 1. Project Overview
Membangun aplikasi manajemen Chatbot (SaaS Mini) di mana user dapat membuat chatbot kustom, mengupload materi pengetahuan (PDF/Text), dan mendapatkan kode embed (iframe) untuk dipasang di website mereka.
Chatbot akan menjawab menggunakan context yang diberikan user, diproses melalui HTTP Request ke Local LLM Engine.

## 2. Tech Stack & Environment
* **Framework:** Laravel 11.x
* **Frontend Stack:** Vue.js 3 (Composition API) via **Inertia.js**.
* **Styling:** Tailwind CSS (configured with Apple Glass Theme).
* **Database:** MySQL.
* **External Service:** Local LLM Engine (running on `http://localhost:8080`).
* **Key Libraries:**
    * `spatie/pdf-to-text` (Linux binary `pdftotext` required) atau `smalot/pdfparser` (Pure PHP).
    * `guzzlehttp/guzzle` (Laravel Http Client).

## 3. Database Schema

### Table: `users` (Default Laravel)
* `id`, `name`, `email`, `password`, etc.

### Table: `chatbots`
* `id` (PK)
* `user_id` (FK to users)
* `name` (String)
* `uuid` (String, Unique, Index) -> *Digunakan untuk public URL iframe*
* `system_prompt` (Text) -> *Default: "Anda adalah chatbot profesional..."*
* `created_at`, `updated_at`

### Table: `knowledge_bases`
* `id` (PK)
* `chatbot_id` (FK to chatbots)
* `type` (Enum: 'text', 'pdf')
* `file_path` (String, Nullable) -> *Jika PDF*
* `content` (LongText) -> *Isi teks mentah atau hasil ekstraksi PDF*
* `created_at`, `updated_at`

## 4. Directory Structure (Key Files)
Pastikan struktur folder mengikuti pola Inertia.js berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── ChatbotController.php       # CRUD Bot (Admin Side)
│   │   └── Public/
│   │       └── ChatWidgetController.php # Public Widget logic
│   └── Requests/
│       └── ChatbotStoreRequest.php
├── Models/
│   ├── Chatbot.php
│   └── KnowledgeBase.php
├── Services/
│   ├── LlmEngineService.php            # Handle HTTP to localhost:8080
│   └── PdfParserService.php            # Handle PDF to Text conversion
routes/
├── web.php                             # All routes (Auth & Public)
resources/
├── js/
│   ├── Components/
│   │   ├── GlassCard.vue               # [NEW] Glassmorphism Container
│   │   ├── GlassButton.vue             # [NEW] Glassmorphism Button
│   │   └── GlassInput.vue              # [NEW] Glassmorphism Input
│   ├── Layouts/
│   │   ├── AuthenticatedLayout.vue     # Dashboard Layout (Refactored to Glass)
│   │   └── WidgetLayout.vue            # Clean Layout for Iframe
│   ├── Pages/
│   │   ├── Chatbots/
│   │   │   ├── Index.vue
│   │   │   └── Create.vue              # Form upload PDF ada disini
│   │   └── Widget/
│   │   │   └── ChatWindow.vue          # UI Chat Bubble
```

## Implementation Phases

### Phase 1: Setup & Auth
Install Laravel, Inertia, Vue, Tailwind using Laravel Breeze/Jetstream.
Setup Database connection.
Run Migrations for users.

### Phase 2: Backend Logic & Database
Create Migration & Models for chatbots and knowledge_bases.
Implement PdfParserService:
Function: extract(UploadedFile $file): string
Logic: Upload file to storage, read text, return string.
Implement LlmEngineService:
Function: generateResponse($systemPrompt, $context, $userMessage)
Logic: Send POST to http://localhost:8080 with constructed prompt.

### Phase 3: Dashboard (CRUD)
ChatbotController:
index: List user's bots.
store: Create bot + handle Knowledge Base input (Save PDF content to DB content column).
show: Show details + Generate Iframe Code.
Example Code: <iframe src="https://domain.com/widget/{uuid}" width="400" height="600"></iframe>

### Phase 4: Public Widget (The Engine)
Route Setup: Route::get('/widget/{uuid}', ...) and Route::post('/widget/{uuid}/send', ...).
ChatWidgetController:
show($uuid): Return Inertia view Widget/ChatWindow with bot details.
sendMessage($uuid, Request $request):
Get Bot & Knowledge Base (content).
Call LlmEngineService.
Return response back to Vue.

### Phase 5: UI/UX (Vue.js)
Create WidgetLayout.vue (No sidebar/header, full height).
Create ChatWindow.vue:
State: messages[]
UI: Chat bubble list (User right, Bot left).
Action: POST to sendMessage endpoint, then append Bot reply to state.

### Phase 6: UI Refactor (Glass Apple Theme)
- **Goal:** Update the entire UI to a modern "Glassmorphism" aesthetic.
- **Components:** Implement `GlassCard`, `GlassButton`, `GlassInput`.
- **Layouts:** Refactor `AuthenticatedLayout` and `GuestLayout` with mesh gradient backgrounds and blur effects.
- **Pages:** Update Login, Register, Dashboard, and CRUD pages to use the new components.

## Business Logic Rules
### PDF Parsing
Ketika user upload PDF:
Simpan file asli di storage/app/public/pdfs.
Ekstrak teks dari PDF tersebut saat itu juga.
Simpan teks hasil ekstraksi ke kolom knowledge_bases.content.
LLM hanya membaca dari kolom content, tidak membaca file PDF mentah saat runtime.

### LLM Prompt Construction
Format prompt yang dikirim ke localhost:8080:

Plaintext
[SYSTEM]
Anda adalah chatbot profesional yang dibentuk untuk membantu menjawab pertanyaan user berdasarkan informasi yang sudah diberikan berikut ini:

--- INFORMASI DASAR ---
{DATABASE_CONTENT_DARI_KNOWLEDGE_BASE}
-----------------------

Jawablah hanya berdasarkan informasi di atas. Jika tidak ada di informasi, katakan saya tidak tahu.

[USER]
{USER_INPUT_MESSAGE}

### LLM API Request Spec
Target: POST http://localhost:8080/v1/chat/completions (or equivalent).
Timeout: 60 seconds.
Error Handling: Jika engine mati/timeout, return JSON: { "reply": "Maaf, server sedang sibuk." }.