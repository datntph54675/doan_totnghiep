class RealtimeOrders {
    constructor() {
        this.pusher = null;
        this.channel = null;
        this.init();
    }

    init() {
        // Initialize Pusher
        this.pusher = new Pusher(window.PUSHER_APP_KEY, {
            cluster: window.PUSHER_APP_CLUSTER,
            encrypted: true
        });

        this.channel = this.pusher.subscribe('orders');
        this.bindEvents();
    }

    bindEvents() {
        // Listen for new orders
        this.channel.bind('order.created', (data) => {
            this.showNotification('Đơn hàng mới', `Đơn hàng ${data.ma_hoa_don} vừa được tạo bởi ${data.user_name}`, 'success');
            this.addNewOrderToTable(data);
        });

        // Listen for order updates
        this.channel.bind('order.updated', (data) => {
            this.showNotification('Cập nhật đơn hàng', `Đơn hàng ${data.ma_hoa_don} đã được cập nhật`, 'info');
            this.updateOrderInTable(data);
        });

        // Listen for order cancellations
        this.channel.bind('order.cancelled', (data) => {
            this.showNotification('Hủy đơn hàng', `Đơn hàng ${data.ma_hoa_don} đã bị hủy`, 'warning');
            this.updateOrderInTable(data);
        });
    }

    showNotification(title, message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <strong>${title}</strong><br>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    addNewOrderToTable(orderData) {
        const tbody = document.querySelector('table tbody');
        if (!tbody) return;

        const newRow = document.createElement('tr');
        newRow.className = 'new-order-highlight';
        newRow.setAttribute('data-order-id', orderData.id);
        
        newRow.innerHTML = `
            <td>${orderData.ma_hoa_don}</td>
            <td>${this.formatDate(orderData.ngay_dat_hang)}</td>
            <td style="color: red; font-weight: bold;">${this.formatCurrency(orderData.tong_tien)}</td>
            <td style="color: ${this.getPaymentMethodColor(orderData.phuong_thuc_thanh_toan)}">${orderData.phuong_thuc_thanh_toan}</td>
            <td class="equal-td">
                <form action="/admin/hoadons/${orderData.id}/update" method="POST">
                    <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                    <input type="hidden" name="_method" value="PUT">
                    <select name="trang_thai" class="form-select w-100" onchange="this.form.submit()" required>
                        <option value="1" selected>Chờ xác nhận</option>
                        <option value="2">Đã xác nhận</option>
                        <option value="3">Đang chuẩn bị</option>
                        <option value="4">Đang vận chuyển</option>
                        <option value="5">Đã giao hàng</option>
                        <option value="6">Đơn hàng đã hủy</option>
                        <option value="7">Đã nhận được hàng</option>
                    </select>
                </form>
            </td>
            <td class="equal-td">
                <form action="/admin/hoadons/${orderData.id}/update" method="POST">
                    <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                    <input type="hidden" name="_method" value="PUT">
                    <select name="trang_thai_thanh_toan" class="form-select badge w-75 text-white p-2 bg-danger text-white" onchange="updateSelectBackground(this); this.form.submit()" id="trang_thai_thanh_toan_${orderData.id}">
                        <option value="Chưa thanh toán" selected>Chưa thanh toán</option>
                        <option value="Đã thanh toán">Đã thanh toán</option>
                        <option value="Thanh toán thất bại">Thanh toán thất bại</option>
                    </select>
                </form>
            </td>
            <td>
                <div class="card-body">
                    <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Thao tác<i class="mdi mdi-chevron-down"></i></button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="/admin/hoadons/${orderData.id}/show">Xem chi tiết</a>
                            <a class="dropdown-item cancel-order" href="#" data-bs-toggle="modal" data-bs-target="#cancelOrderModal" data-id="${orderData.id}">Hủy đơn hàng</a>
                        </div>
                    </div>
                </div>
            </td>
        `;

        // Insert at the beginning of the table
        tbody.insertBefore(newRow, tbody.firstChild);

        // Add highlight effect
        setTimeout(() => {
            newRow.classList.remove('new-order-highlight');
        }, 3000);

        // Reinitialize event listeners for new elements
        this.initializeEventListeners();
    }

    updateOrderInTable(orderData) {
        const row = document.querySelector(`tr[data-order-id="${orderData.id}"]`);
        if (!row) return;

        // Update status cells
        const statusCell = row.querySelector('td:nth-child(5) select[name="trang_thai"]');
        const paymentStatusCell = row.querySelector('td:nth-child(6) select[name="trang_thai_thanh_toan"]');

        if (statusCell && orderData.trang_thai) {
            statusCell.value = orderData.trang_thai;
        }

        if (paymentStatusCell && orderData.trang_thai_thanh_toan) {
            paymentStatusCell.value = orderData.trang_thai_thanh_toan;
            this.updateSelectBackground(paymentStatusCell);
        }

        // Add update highlight
        row.classList.add('updated-order-highlight');
        setTimeout(() => {
            row.classList.remove('updated-order-highlight');
        }, 2000);
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount);
    }

    getPaymentMethodColor(method) {
        return method === 'Thanh toán qua chuyển khoản ngân hàng' ? 'blue' : 
               method === 'Thanh toán khi nhận hàng' ? 'red' : 'black';
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    updateSelectBackground(selectElement) {
        const selectedValue = selectElement.value;
        const selectClassList = selectElement.classList;

        selectClassList.remove('bg-danger', 'bg-success', 'bg-secondary');

        if (selectedValue === 'Chưa thanh toán') {
            selectClassList.add('bg-danger');
        } else if (selectedValue === 'Đã thanh toán') {
            selectClassList.add('bg-success');
        } else if (selectedValue === 'Thanh toán thất bại') {
            selectClassList.add('bg-secondary');
        }
    }

    initializeEventListeners() {
        // Reinitialize select change listeners
        const selects = document.querySelectorAll('.form-select');
        selects.forEach((selectElement) => {
            selectElement.removeEventListener('change', this.confirmSubmit);
            selectElement.addEventListener('change', (e) => this.confirmSubmit(e.target));
        });

        // Reinitialize cancel button listeners
        const cancelButtons = document.querySelectorAll('.cancel-order');
        cancelButtons.forEach(button => {
            button.removeEventListener('click', this.handleCancelClick);
            button.addEventListener('click', (e) => this.handleCancelClick(e));
        });
    }

    confirmSubmit(selectElement) {
        const form = selectElement.form;
        const selectedOption = selectElement.options[selectElement.selectedIndex].text;
        const defaultValue = selectElement.getAttribute('data-default-value');

        if (confirm('Bạn có chắc chắn thay đổi trạng thái thành "' + selectedOption + '" không?')) {
            form.submit();
        } else {
            selectElement.value = defaultValue;
        }
    }

    handleCancelClick(event) {
        const orderId = event.target.getAttribute('data-id');
        const form = document.getElementById('cancelOrderForm');
        form.action = `/admin/hoadons/${orderId}/destroy`;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Pusher !== 'undefined') {
        window.realtimeOrders = new RealtimeOrders();
    }
});
