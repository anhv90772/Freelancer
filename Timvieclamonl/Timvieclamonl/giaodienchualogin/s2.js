<script>
    document.getElementById('toggle-skills').addEventListener('click', function(event) {
        event.preventDefault(); // Ngăn chặn hành vi mặc định
        const submenu = document.getElementById('skills-submenu');
        submenu.style.display = (submenu.style.display === 'block') ? 'none' : 'block'; // Chuyển đổi hiển thị
    });
</script>