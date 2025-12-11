# Contoh Penggunaan JSON Arguments

File ini berisi contoh struktur JSON yang dapat digunakan dengan WP-CLI Models package untuk berbagai jenis operasi.

## Struktur JSON

JSON telah dibagi menjadi beberapa bagian berdasarkan tipe operasi yang berbeda:

1. `menu_args` - Untuk membuat menu
2. `create_post_args` - Untuk membuat post
3. `create_term_args` - Untuk membuat term/taxonomy
4. `custom_item_menu_args` - Untuk menambahkan item kustom ke menu
5. `post_item_menu_args` - Untuk menambahkan post ke menu
6. `term_item_menu_args` - Untuk menambahkan term ke menu

## Cara Penggunaan

Anda dapat menggunakan file JSON ini sebagai template untuk membuat argument yang akan digunakan dalam aplikasi Anda. Setiap bagian dapat digunakan secara independen sesuai dengan kebutuhan.

Contoh penggunaan dalam kode PHP:

```php
use Vigihdev\WpCliModels\Support\Transformers\FilepathDtoTransformer;
use Vigihdev\WpCliModels\DTOs\Args\Post\CreatePostArgsDto;

// Membaca dari file JSON
$dto = FilepathDtoTransformer::fromFileJson(
    filepath: '/path/to/your/json/file.json', 
    dtoClass: CreatePostArgsDto::class
);

// Atau langsung dari string JSON
$jsonString = '{"title":"Post Title","content":"Post Content","author":1}';
$dto = FilepathDtoTransformer::fromJson(
    json: $jsonString,
    dtoClass: CreatePostArgsDto::class
);
```

## Catatan Penting

- Parameter wajib harus selalu disertakan
- Parameter opsional dapat dihilangkan jika tidak diperlukan
- Pastikan tipe data sesuai dengan yang diharapkan oleh masing-masing DTO
- Validasi akan dilakukan secara otomatis saat membuat instance DTO dari array