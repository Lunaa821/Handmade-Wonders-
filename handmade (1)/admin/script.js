function showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.content');
    sections.forEach(section => section.classList.remove('active'));

    // Show the selected section
    const sectionToShow = document.getElementById(sectionId);
    if (sectionToShow) {
        sectionToShow.classList.add('active');
    }
}


// Giả sử bạn muốn thực hiện một số thao tác khi form được gửi
document.querySelector("form").addEventListener("submit", function(event) {
    const productName = document.querySelector("input[name='product_name']").value;
    const productPrice = document.querySelector("input[name='product_price']").value;
    
    if (!productName || !productPrice) {
        alert("Vui lòng điền đầy đủ thông tin!");
        event.preventDefault(); // Ngừng form submit nếu dữ liệu không hợp lệ
    }
});

// Hàm fetch dữ liệu người dùng
async function fetchUsers() {
    try {
        const response = await fetch('fetch_users.php'); // Gọi file PHP
        const users = await response.json(); // Parse JSON

        const userListBody = document.querySelector("#user-list tbody");

        // Xóa dữ liệu cũ
        userListBody.innerHTML = "";

        // Duyệt qua danh sách và thêm từng dòng vào bảng
        users.forEach(user => {
            const row = `
                <tr>
                    <td>${user.user_id}</td>
                    <td>${user.full_name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone_number || 'N/A'}</td>
                    <td>${user.address || 'N/A'}</td>
                    <td>${user.role || 'N/A'}</td>
                    <td>${user.created_at}</td>
                    <td>
                            <button class="edit" onclick="editUser(${user.user_id}, '${user.full_name}', '${user.email}', '${user.phone_number}', '${user.address}')">Sửa</button>
                            <button class="delete" onclick="deleteUser(${user.user_id})">Xóa</button>
                        </td>
                </tr>
            `;
            userListBody.innerHTML += row;
        });
    } catch (error) {
        console.error("Lỗi khi tải dữ liệu người dùng:", error);
    }
}

// Hàm xóa người dùng
function deleteUser(userId) {
    // Xác nhận trước khi xóa
    if (confirm("Bạn có chắc chắn muốn xóa người dùng này?")) {
        // Tạo dữ liệu gửi lên server
        const formData = new URLSearchParams();
        formData.append('action', 'delete'); // Hành động xóa
        formData.append('user_id', userId);  // ID của người dùng cần xóa

        // Gửi yêu cầu POST bằng fetch
        fetch('fetch_users.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Chuyển kết quả từ server về JSON
        .then(result => {
            // Hiển thị thông báo thành công hoặc thất bại
            alert(result.message);
            if (result.status === 'success') {
                // Xóa dòng tương ứng trên bảng HTML
                const row = document.getElementById(`row-${userId}`);
                if (row) row.remove();
                // Tải lại trang sau khi xóa thành công
                location.reload();  // Tải lại trang
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Đã xảy ra lỗi khi xóa người dùng.');
        });
    }
}

// Gọi hàm fetchUsers khi trang được load
document.addEventListener("DOMContentLoaded", fetchUsers);
