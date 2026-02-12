<?php
require_once 'config.php';
require_once 'functions.php';

requireLogin();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($user['username']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0px 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        .header h1 {
            color: #222;
            font-size: 28px;
        }

        .logout-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0px 10px 20px rgba(102, 126, 234, 0.3);
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .welcome-section h2 {
            color: #222;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: #666;
            font-size: 16px;
        }

        .user-info {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .user-info h3 {
            color: #222;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .info-item {
            display: flex;
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: 600;
            color: #333;
            width: 120px;
        }

        .info-value {
            color: #666;
        }

        .actions {
            text-align: center;
        }

        .action-btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 0 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0px 10px 20px rgba(102, 126, 234, 0.3);
        }

        .action-btn.secondary {
            background: #e0e0e0;
            color: #333;
        }

        .action-btn.secondary:hover {
            background: #d0d0d0;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .actions {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .action-btn {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Dashboard</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="welcome-section">
            <h2>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h2>
            <p>You are successfully logged in to your Cytti Homes account.</p>
        </div>

        <div class="user-info">
            <h3>Your Account Information</h3>
            <div class="info-item">
                <span class="info-label">Username:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['username']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">User ID:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['id']); ?></span>
            </div>
        </div>

        <div class="actions">
            <a href="properties.html" class="action-btn">Browse Properties</a>
            <a href="profile.html" class="action-btn secondary">Edit Profile</a>
        </div>
    </div>
</body>
</html>
