// Hàm thêm sản phẩm vào giỏ hàng và chuyển hướng
function addToCartAndRedirect(productId, cartIndexUrl) {
    muaNgay(productId)
        .then(() => {
            console.log("Mua đơn hàng thành công, chuyển hướng...");
            window.location.href = cartIndexUrl; // Chuyển hướng đến giỏ hàng
        })
        .catch(error => {
            console.error("Có lỗi xảy ra:", error);
            alert("Không thể mua đơn hàng này. Vui lòng thử lại.");
        });

        var dungLuongId;
        var mauSacId;

        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.tp-size-variation-btn');
            const colorButtons = document.querySelectorAll('.tp-color-variation-btn');
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    buttons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    dungLuongId = parseInt(button.getAttribute('data-dung-luong-id'));
                });
            });
            colorButtons.forEach(button => {
                button.addEventListener('click', () => {
                    colorButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    mauSacId = parseInt(button.getAttribute('data-mau-sac-id'));
                });
            });
        });
        
       
    }        
    async function muaNgay(productId) {
        try {
            const quantityInput = document.querySelector('.tp-cart-input');
            const quantity = parseInt(quantityInput?.value) || 1;
    
            if (quantity < 1) {
                alertify.error("Số lượng sản phẩm phải lớn hơn 0");
                throw new Error("Invalid quantity");
            }
    
            if (typeof mauSacId === 'undefined') {
                alertify.error("Vui lòng chọn màu sắc sản phẩm!");
                throw new Error("Color not selected");
            }
    
            if (typeof dungLuongId === 'undefined') {
                alertify.error("Vui lòng chọn dung lượng sản phẩm!");
                throw new Error("Capacity not selected");
            }
    
            const canAdd = await checkQuantityLimit(mauSacId, dungLuongId, productId, quantity);
            if (!canAdd) {
                alertify.error("Số lượng vượt mức cho phép!");
                throw new Error("Exceed limit");
            }
    
            const response = await $.ajax({
                url: "/Add-Cart/" + productId,
                type: "GET",
                data: {
                    quantity,
                    mauSacId,
                    dungLuongId,
                }
            });
    
            RenderCartDrop(response.html);
            alertify.success('Đã mua thành công');
            return true;
    
        } catch (err) {
            alertify.error("Mua sản phẩm thất bại!");
            throw err; // Quan trọng: ném lỗi để chặn then()
        }
    }