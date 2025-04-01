<div class="top-bar">
    <div class="user-info">
        <span>Welcome, <?php echo $_SESSION['username']; ?>!</span>
        <div class="dropdown">
            <button class="dropdown-btn"><i class="fa-solid fa-angle-down"></i></button>
            <div class="dropdown-content">
                <a href="../index.php?action=logout">Logout</a>
            </div>
        </div>
    </div>
</div>