<?php
session_start();
require_once __DIR__ . '/../includes/database.php';

// Initialize variables
$errors = [];
$formData = [
    'fullname' => '',
    'username' => '',
    'email' => '',
    'genre' => ''
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
    // Sanitize and store form data
    $formData = [
        'fullname' => htmlspecialchars(trim($_POST["fullname"])),
        'username' => htmlspecialchars(trim($_POST["username"])),
        'email' => htmlspecialchars(trim($_POST["email"])),
        'genre' => htmlspecialchars(trim($_POST["genre"]))
    ];
    
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validation (same as before)
    // ... [keep all your validation code from previous example]

    // If no errors, proceed with registration
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
       // Replace your current insert code with this prepared statement:
$stmt = $conn->prepare("INSERT INTO users (full_name, user_name, email, password, favorite_music_genre) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $formData['fullname'], $formData['username'], $formData['email'], $passwordHash, $formData['genre']);

if ($stmt->execute()) {
    $_SESSION['registration_success'] = true;
    header("Location: login.php");
    exit();
} else {
    $errors[] = "Registration failed: " . $conn->error;
}
$stmt->close();
    }
}

// The HTML part starts here
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Drop The Beat</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-bg: #1b1b2f;
      --secondary-bg: #162447;
      --accent-color: #e43f5a;
      --text-color: #f1f1f1;
      --text-secondary: #b8b8b8;
      --error-color: #ff4444;
      --success-color: #00C851;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: var(--primary-bg);
      color: var(--text-color);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      position: relative;
      overflow: hidden;
    }

    .music-note {
      position: absolute;
      color: rgba(228, 63, 90, 0.1);
      font-size: 2rem;
      animation: float 8s infinite ease-in-out;
      z-index: 0;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-30px) rotate(15deg); }
    }

    .form-container {
      background: rgba(22, 36, 71, 0.8);
      backdrop-filter: blur(10px);
      padding: 3rem 2.5rem;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 450px;
      z-index: 1;
      position: relative;
      border: 1px solid rgba(228, 63, 90, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }

    .form-container h2 {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 1.5rem;
      background: linear-gradient(to right, #e43f5a, #ff7b54);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .form-container p.subtitle {
      text-align: center;
      color: var(--text-secondary);
      margin-bottom: 2rem;
      font-size: 0.95rem;
    }

    .alert {
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }

    .alert-danger {
      background-color: rgba(255, 68, 68, 0.2);
      border-left: 4px solid var(--error-color);
      color: var(--text-color);
    }

    .alert-success {
      background-color: rgba(0, 200, 81, 0.2);
      border-left: 4px solid var(--success-color);
      color: var(--text-color);
    }

    .input-group {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .input-group i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--accent-color);
    }

    .input-group input,
    .input-group select {
      width: 100%;
      padding: 12px 15px 12px 45px;
      background: rgba(27, 27, 47, 0.5);
      border: 1px solid rgba(228, 63, 90, 0.3);
      border-radius: 8px;
      color: var(--text-color);
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .input-group select {
      appearance: none;
      padding-right: 30px;
    }

    .select-arrow {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--accent-color);
      pointer-events: none;
    }

    .input-group input:focus,
    .input-group select:focus {
      outline: none;
      border-color: var(--accent-color);
      box-shadow: 0 0 0 3px rgba(228, 63, 90, 0.2);
    }

    .input-group input::placeholder {
      color: var(--text-secondary);
    }

    .checkbox-group {
      display: flex;
      align-items: flex-start;
      margin: 1.5rem 0;
    }

    .checkbox-group input {
      margin-right: 10px;
      margin-top: 3px;
      accent-color: var(--accent-color);
    }

    .checkbox-group label {
      font-size: 0.9rem;
      color: var(--text-secondary);
    }

    .checkbox-group a {
      color: var(--accent-color);
      text-decoration: none;
      transition: opacity 0.3s ease;
    }

    .checkbox-group a:hover {
      opacity: 0.8;
    }

    .register-btn {
      background: linear-gradient(45deg, var(--accent-color), #ff7b54);
      color: white;
      padding: 14px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 15px rgba(228, 63, 90, 0.3);
      width: 100%;
      position: relative;
    }

    .register-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(228, 63, 90, 0.4);
    }

    .login-link {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.95rem;
      color: var(--text-secondary);
    }

    .login-link a {
      color: var(--accent-color);
      text-decoration: none;
      font-weight: 600;
      transition: opacity 0.3s ease;
    }

    .login-link a:hover {
      opacity: 0.8;
    }

    .password-strength {
      height: 4px;
      background: rgba(27, 27, 47, 0.5);
      border-radius: 2px;
      margin-top: 5px;
      overflow: hidden;
      position: relative;
    }

    .strength-meter {
      height: 100%;
      width: 0;
      background: var(--accent-color);
      transition: width 0.3s ease, background 0.3s ease;
    }

    .toggle-password {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--accent-color);
      cursor: pointer;
    }

    @media (max-width: 480px) {
      .form-container {
        padding: 2rem 1.5rem;
        margin: 0 1rem;
      }
      
      .form-container h2 {
        font-size: 1.8rem;
      }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-container h2,
    .form-container p.subtitle,
    .input-group,
    .checkbox-group,
    .register-btn,
    .login-link {
      animation: fadeIn 0.6s ease forwards;
    }

    .form-container p.subtitle { animation-delay: 0.1s; }
    .input-group:nth-child(1) { animation-delay: 0.2s; }
    .input-group:nth-child(2) { animation-delay: 0.3s; }
    .input-group:nth-child(3) { animation-delay: 0.4s; }
    .input-group:nth-child(4) { animation-delay: 0.5s; }
    .input-group:nth-child(5) { animation-delay: 0.6s; }
    .checkbox-group { animation-delay: 0.7s; }
    .register-btn { animation-delay: 0.8s; }
    .login-link { animation-delay: 0.9s; }
  </style>
</head>
<body>
  <!-- Floating music notes in background -->
  <i class="music-note fas fa-music" style="top: 15%; left: 10%; animation-delay: 0s;"></i>
  <i class="music-note fas fa-music" style="top: 75%; left: 15%; animation-delay: 1s;"></i>
  <i class="music-note fas fa-music" style="top: 40%; left: 85%; animation-delay: 2s;"></i>
  <i class="music-note fas fa-music" style="top: 85%; left: 80%; animation-delay: 1.5s;"></i>
  <i class="music-note fas fa-music" style="top: 10%; left: 50%; animation-delay: 0.5s;"></i>
  <i class="music-note fas fa-music" style="top: 60%; left: 25%; animation-delay: 2.5s;"></i>
  <i class="music-note fas fa-music" style="top: 30%; left: 70%; animation-delay: 1.8s;"></i>

   <div class="form-container">
    <h2>Join Drop The Beat</h2>
    <p class="subtitle">Create your account to start your musical journey</p>

    <!-- Error messages -->
    <?php if (!empty($errors)): ?>
      <div id="error-container">
        <?php foreach ($errors as $error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="Register.php" method="POST">
      <!-- Form fields with PHP values -->
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="fullname" placeholder="Full Name" required 
               value="<?= htmlspecialchars($formData['fullname']) ?>">
      </div>

      <div class="input-group">
        <i class="fas fa-at"></i>
        <input type="text" name="username" placeholder="Username" required
               value="<?php echo isset($_SESSION['formData']['username']) ? htmlspecialchars($_SESSION['formData']['username']) : ''; ?>">
      </div>

      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" placeholder="Email Address" required
               value="<?php echo isset($_SESSION['formData']['email']) ? htmlspecialchars($_SESSION['formData']['email']) : ''; ?>">
      </div>

      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required id="password">
        <i class="fas fa-eye toggle-password"></i>
        <div class="password-strength">
          <div class="strength-meter" id="strength-meter"></div>
        </div>
      </div>

      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <i class="fas fa-eye toggle-password"></i>
      </div>

      <div class="input-group">
        <i class="fas fa-music"></i>
        <select name="genre" id="genre" required>
          <option value="">Favorite Music Genre</option>
          <option value="rock" <?php echo (isset($_SESSION['formData']['genre']) && $_SESSION['formData']['genre'] === 'rock') ? 'selected' : ''; ?>>Rock</option>
          <option value="pop" <?php echo (isset($_SESSION['formData']['genre']) && $_SESSION['formData']['genre'] === 'pop') ? 'selected' : ''; ?>>Pop</option>
          <option value="hiphop" <?php echo (isset($_SESSION['formData']['genre']) && $_SESSION['formData']['genre'] === 'hiphop') ? 'selected' : ''; ?>>Hip-Hop & R&B</option>
          <option value="electronic" <?php echo (isset($_SESSION['formData']['genre']) && $_SESSION['formData']['genre'] === 'electronic') ? 'selected' : ''; ?>>Electronic</option>
          <option value="classical" <?php echo (isset($_SESSION['formData']['genre']) && $_SESSION['formData']['genre'] === 'classical') ? 'selected' : ''; ?>>Classical</option>
          <option value="jazz" <?php echo (isset($_SESSION['formData']['genre']) && $_SESSION['formData']['genre'] === 'jazz') ? 'selected' : ''; ?>>Jazz</option>
          <option value="other" <?php echo (isset($_SESSION['formData']['genre']) && $_SESSION['formData']['genre'] === 'other') ? 'selected' : ''; ?>>Other</option>
        </select>
        <i class="fas fa-chevron-down select-arrow"></i>
      </div>

      <div class="checkbox-group">
        <input type="checkbox" id="terms" required>
        <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
      </div>

      <button type="submit" name="submit" class="register-btn">
        <span class="btn-text">Create Account</span>
      </button>
    </form>

    <p class="login-link">Already have an account? <a href="../html/login.html">Sign In</a></p>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add more floating music notes dynamically
      const body = document.querySelector('body');
      for (let i = 0; i < 5; i++) {
        const note = document.createElement('i');
        note.className = 'music-note fas fa-music';
        const top = Math.random() * 100;
        const left = Math.random() * 100;
        const delay = Math.random() * 5;
        const duration = 5 + Math.random() * 5;
        const size = 1 + Math.random() * 2;
        note.style.top = `${top}%`;
        note.style.left = `${left}%`;
        note.style.animationDelay = `${delay}s`;
        note.style.animationDuration = `${duration}s`;
        note.style.fontSize = `${size}rem`;
        note.style.opacity = 0.1 + Math.random() * 0.2;
        body.appendChild(note);
      }

      // Password strength meter
      const passwordInput = document.getElementById('password');
      const strengthMeter = document.getElementById('strength-meter');
      
      passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        if (password.length >= 8) strength += 1;
        if (password.length >= 12) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        
        const width = strength * 25;
        let color = '#e43f5a';
        
        if (strength >= 4) {
          color = '#4CAF50';
        } else if (strength >= 2) {
          color = '#FFC107';
        }
        
        strengthMeter.style.width = `${width}%`;
        strengthMeter.style.background = color;
      });

      // Toggle password visibility
      document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
          const input = this.previousElementSibling;
          const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
          input.setAttribute('type', type);
          this.classList.toggle('fa-eye-slash');
        });
      });

      // Clear form data from session when page loads
      <?php unset($_SESSION['formData']); ?>
    });
  </script>
</body>
</html>