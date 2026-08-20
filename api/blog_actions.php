<?php
/**
 * API: Blog Actions (Create, Edit, Delete)
 * Hontech Auto Center Inc.
 */

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../admin/login.php?error=unauthorized");
    exit;
}

// Ensure role has blog editing permissions
$allowed_roles = ['super_admin', 'marketing', 'manager'];
if (!in_array($_SESSION['user_role'] ?? '', $allowed_roles)) {
    die("Unauthorized action.");
}

$action = $_POST['action'] ?? ($_GET['action'] ?? '');
$pdo = getDBConnection();

if (!$pdo) {
    header("Location: ../admin/posts.php?error=db_offline");
    exit;
}

if ($action === 'delete') {
    $post_id = intval($_GET['id'] ?? 0);
    if ($post_id > 0) {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute([':id' => $post_id]);
    }
    header("Location: ../admin/posts.php?msg=deleted");
    exit;
}

if ($action === 'save') {
    $post_id = intval($_POST['post_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 1);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = in_array($_POST['status'] ?? '', ['draft', 'published']) ? $_POST['status'] : 'published';

    if (empty($title) || empty($excerpt) || empty($content)) {
        header("Location: ../admin/post-editor.php?" . ($post_id ? "id=$post_id&" : "") . "error=empty_fields");
        exit;
    }

    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    // Handle featured image upload
    $featured_image = 'images/service-image.png';
    if (!empty($_POST['current_image'])) {
        $featured_image = $_POST['current_image'];
    }

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['featured_image']['tmp_name'];
        $fileName = basename($_FILES['featured_image']['name']);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $uploadDir = __DIR__ . '/../uploads/blogs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $newFileName = 'blog_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                $featured_image = 'uploads/blogs/' . $newFileName;
            }
        }
    }

    if ($post_id > 0) {
        // Update existing post
        $stmt = $pdo->prepare("
            UPDATE posts SET 
                title = :title, 
                category_id = :cat_id, 
                excerpt = :excerpt, 
                content = :content, 
                featured_image = :img, 
                status = :status 
            WHERE id = :id
        ");
        $stmt->execute([
            ':title'   => $title,
            ':cat_id'  => $category_id,
            ':excerpt' => $excerpt,
            ':content' => $content,
            ':img'     => $featured_image,
            ':status'  => $status,
            ':id'      => $post_id
        ]);
        header("Location: ../admin/posts.php?msg=updated");
        exit;
    } else {
        // Insert new post
        $stmt = $pdo->prepare("
            INSERT INTO posts (
                category_id, author_id, title, slug, excerpt, content, featured_image, status
            ) VALUES (
                :cat_id, :author_id, :title, :slug, :excerpt, :content, :img, :status
            )
        ");
        $stmt->execute([
            ':cat_id'    => $category_id,
            ':author_id' => $_SESSION['user_id'] ?? 1,
            ':title'     => $title,
            ':slug'      => $slug,
            ':excerpt'   => $excerpt,
            ':content'   => $content,
            ':img'       => $featured_image,
            ':status'    => $status
        ]);
        header("Location: ../admin/posts.php?msg=created");
        exit;
    }
}
