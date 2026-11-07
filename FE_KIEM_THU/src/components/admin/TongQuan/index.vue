<template>
  <div class="maison-dashboard" data-testid="page-tong-quan">
    
    <!-- SUB-HEADER & CHÀO BUỔI SÁNG -->
    <div class="dash-header">
      <div class="dash-title-wrap">
        <div class="breadcrumb-meta">
          <span>BOUTIQUE OPERATIONS &gt; FLAGSHIP Q1</span>
          <span class="pos-sync-tag">Live POS Sync</span>
        </div>
        <h1 class="greeting-title" data-testid="title-tong-quan">Chào buổi sáng, Mai Anh</h1>
        <p class="greeting-subtitle">Hệ thống ghi nhận lưu lượng khách ghé thăm tăng 22% vào khung giờ sáng.</p>
      </div>

      <div class="dash-controls">
        <div class="time-pill-group">
          <button type="button" :class="['btn-time', thoiGianHienTai === 'hom_nay' ? 'active' : '']" @click="chonThoiGian('hom_nay')">Hôm nay</button>
          <button type="button" :class="['btn-time', thoiGianHienTai === '7_ngay' ? 'active' : '']" @click="chonThoiGian('7_ngay')">7 ngày qua</button>
          <button type="button" :class="['btn-time', thoiGianHienTai === 'thang_nay' ? 'active' : '']" @click="chonThoiGian('thang_nay')">Tháng này</button>
        </div>

        <button type="button" class="btn-report" @click="xuatBaoCao">
          <span class="icon">📄</span> Xuất báo cáo
        </button>

        <button type="button" class="btn-new-order" data-testid="btn-tao-don-moi" @click="$emit('chuyen-tab', 'quan_ly_don_hang')">
          <span class="icon">+</span> Tạo đơn hàng mới <span class="shortcut">F2</span>
        </button>
      </div>
    </div>

    <!-- 4 CARD KPI CHÍNH -->
    <div class="kpi-grid">
      <!-- 1. DOANH THU -->
      <div class="kpi-card clickable-card" data-testid="card-doanh-thu" @click="xemChiTietDoanhThu" title="Click để xem chi tiết báo cáo doanh số">
        <div class="kpi-meta">
          <span class="kpi-label">DOANH THU {{ thoiGianLabel.toUpperCase() }}</span>
        </div>
        <div class="kpi-body">
          <div class="kpi-val-wrap">
            <h2 class="kpi-number" data-testid="val-doanh-thu">{{ kpiData.doanhThu }}</h2>
            <span class="kpi-unit">đ</span>
          </div>
          <div class="kpi-sparkline">
            <svg viewBox="0 0 70 24" class="sparkline-svg">
              <path d="M0 20 Q18 22 35 12 T70 4" fill="none" stroke="#f97316" stroke-width="2.5" />
            </svg>
          </div>
        </div>
        <div class="kpi-footer">
          <span class="trend-up">↑ +14.2%</span>
          <span class="trend-compare">vs kỳ trước</span>
        </div>
      </div>

      <!-- 2. SỐ ĐƠN HÀNG MỚI -->
      <div class="kpi-card clickable-card" data-testid="card-don-hang" @click="$emit('chuyen-tab', 'quan_ly_don_hang')" title="Click để chuyển sang Quản lý Đơn hàng">
        <div class="kpi-meta">
          <span class="kpi-label">SỐ ĐƠN HÀNG MỚI</span>
          <span class="kpi-icon-box">🛍️</span>
        </div>
        <div class="kpi-body">
          <div class="kpi-val-wrap">
            <h2 class="kpi-number" data-testid="val-don-hang">{{ kpiData.donHang }}</h2>
            <span class="kpi-unit">đơn</span>
          </div>
        </div>
        <div class="kpi-footer text-muted-meta">
          <span>● <strong>{{ kpiData.donTaiQuay }}</strong> tại quầy</span>
          <span class="dot-sep">•</span>
          <span><strong>{{ kpiData.donOnline }}</strong> Online / IG</span>
        </div>
      </div>

      <!-- 3. SẢN PHẨM BÁN RA -->
      <div class="kpi-card clickable-card" data-testid="card-san-pham" @click="$emit('chuyen-tab', 'quan_ly_san_pham')" title="Click để xem Danh sách Sản phẩm">
        <div class="kpi-meta">
          <span class="kpi-label">SẢN PHẨM BÁN RA</span>
          <span class="kpi-icon-box">📦</span>
        </div>
        <div class="kpi-body">
          <div class="kpi-val-wrap">
            <h2 class="kpi-number" data-testid="val-san-pham">{{ kpiData.sanPham }}</h2>
            <span class="kpi-unit">món</span>
          </div>
        </div>
        <div class="kpi-footer">
          <span class="tag-top-cat">Top category: Blazer &amp; Đầm Maxi...</span>
        </div>
      </div>

      <!-- 4. CẢNH BÁO TỒN KHO -->
      <div class="kpi-card kpi-warning clickable-card" data-testid="card-canh-bao" @click="$emit('chuyen-tab', 'quan_ly_san_pham')" title="Click để chuyển đến kho xử lý">
        <div class="kpi-meta">
          <span class="kpi-label text-danger">CẢNH BÁO TỒN KHO</span>
          <span class="kpi-icon-box box-danger">⚠️</span>
        </div>
        <div class="kpi-body">
          <div class="kpi-val-wrap">
            <h2 class="kpi-number text-danger" data-testid="val-ton-kho">07</h2>
            <span class="kpi-unit text-danger">SKU</span>
          </div>
        </div>
        <div class="kpi-footer">
          <a href="#" class="link-restock" @click.prevent="$emit('chuyen-tab', 'quan_ly_san_pham')">Hết size S &amp; M... Nhập kho ngay →</a>
        </div>
      </div>
    </div>

    <!-- HÀNG 2: BIỂU ĐỒ DOANH SỐ & VẬN HÀNH ĐƠN HÀNG -->
    <div class="mid-grid">
      <!-- Cột trái: Nhịp điệu Doanh số theo Giờ -->
      <div class="content-panel panel-left">
        <div class="panel-header-line">
          <div>
            <h3 class="panel-heading">Nhịp điệu Doanh số theo Giờ</h3>
            <span class="panel-subtext">Ghi nhận dữ liệu thực từ 09:00 đến hiện tại</span>
          </div>
          <div class="chart-legend">
            <span class="legend-item"><span class="legend-box box-black"></span> Doanh thu (triệu đ)</span>
            <span class="legend-item"><span class="legend-box box-orange"></span> Lượng khách thử đồ</span>
          </div>
        </div>

        <!-- Biểu đồ cột tĩnh thanh lịch chuẩn ảnh -->
        <div class="bar-chart-container">
          <div class="chart-bars">
            <div class="bar-col">
              <div class="bar-fill" style="height: 38%;"></div>
              <span class="bar-time">09:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-fill" style="height: 52%;"></div>
              <span class="bar-time">10:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-fill" style="height: 68%;"></div>
              <span class="bar-time">11:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-fill" style="height: 62%;"></div>
              <span class="bar-time">12:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-fill" style="height: 48%;"></div>
              <span class="bar-time">13:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-fill" style="height: 75%;"></div>
              <span class="bar-time">14:00</span>
            </div>
            <!-- Cột đỉnh 15:00 màu đen có tooltip -->
            <div class="bar-col col-peak">
              <div class="peak-tooltip">21.4M (Đỉnh)</div>
              <div class="bar-fill bar-peak" style="height: 94%;"></div>
              <span class="bar-time font-bold">15:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-fill" style="height: 58%;"></div>
              <span class="bar-time">16:00</span>
            </div>
            <div class="bar-col">
              <div class="bar-fill" style="height: 40%;"></div>
              <span class="bar-time">17:00</span>
            </div>
          </div>
        </div>

        <!-- 4 Thanh tỷ lệ danh mục bán chạy -->
        <div class="category-breakdown">
          <div class="cat-progress-card">
            <div class="cat-meta">
              <span class="cat-name">VÁY &amp; ĐẦM MAXI</span>
              <strong class="cat-pct">42% (20.4M)</strong>
            </div>
            <div class="progress-bar"><div class="progress-fill fill-blue" style="width: 42%;"></div></div>
          </div>

          <div class="cat-progress-card">
            <div class="cat-meta">
              <span class="cat-name">ÁO SƠ MI &amp; BLAZER</span>
              <strong class="cat-pct">35% (17.0M)</strong>
            </div>
            <div class="progress-bar"><div class="progress-fill fill-orange" style="width: 35%;"></div></div>
          </div>

          <div class="cat-progress-card">
            <div class="cat-meta">
              <span class="cat-name">QUẦN ÂU &amp; CHÂN VÁY</span>
              <strong class="cat-pct">15% (7.3M)</strong>
            </div>
            <div class="progress-bar"><div class="progress-fill fill-green" style="width: 15%;"></div></div>
          </div>

          <div class="cat-progress-card">
            <div class="cat-meta">
              <span class="cat-name">PHỤ KIỆN &amp; KHĂN</span>
              <strong class="cat-pct">8% (3.9M)</strong>
            </div>
            <div class="progress-bar"><div class="progress-fill fill-gray" style="width: 8%;"></div></div>
          </div>
        </div>
      </div>

      <!-- Cột phải: Vận hành Đơn hàng (Tiến trình 4 bước) -->
      <div class="content-panel panel-right">
        <div class="panel-header-line">
          <h3 class="panel-heading">Vận hành Đơn hàng</h3>
          <span class="order-total-badge">86 đơn hôm nay</span>
        </div>
        <p class="panel-subtext" style="margin-bottom: 14px;">Tiến độ điều phối từ lúc chốt bill đến khi hoàn tất giao nhận</p>

        <!-- 4 Step Card -->
        <div class="workflow-steps">
          <!-- Step 1 -->
          <div class="step-card clickable-card" @click="$emit('chuyen-tab', 'quan_ly_don_hang')" title="Click để xem 12 đơn chờ xác nhận">
            <div class="step-num">01</div>
            <div class="step-content">
              <strong>Chờ xác nhận &amp; chốt size</strong>
              <small>Chủ yếu đơn hẹn online Instagram</small>
            </div>
            <div class="step-count">
              <strong>12</strong>
              <span>đơn</span>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="step-card clickable-card" @click="$emit('chuyen-tab', 'quan_ly_don_hang')" title="Click để xem 19 đơn đã đóng gói">
            <div class="step-num">02</div>
            <div class="step-content">
              <strong>Đã đóng gói &amp; Bọc giấy nến</strong>
              <small>Kèm thiệp cảm ơn Atelier</small>
            </div>
            <div class="step-count">
              <strong>19</strong>
              <span>đơn</span>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="step-card clickable-card" @click="$emit('chuyen-tab', 'quan_ly_don_hang')" title="Click để xem 14 đơn đang giao">
            <div class="step-num">03</div>
            <div class="step-content">
              <strong>Đang giao Hỏa tốc (Grab/Aha)</strong>
              <small>Nội thành TP.HCM (Quận 1, 2, 3, 7)</small>
            </div>
            <div class="step-count count-highlight">
              <strong>14</strong>
              <span>đơn</span>
            </div>
          </div>

          <!-- Step 4 -->
          <div class="step-card step-done clickable-card" @click="$emit('chuyen-tab', 'quan_ly_don_hang')" title="Click để xem 41 đơn hoàn tất">
            <div class="step-num icon-check">✓</div>
            <div class="step-content">
              <strong>Hoàn tất &amp; Khách nhận đồ</strong>
              <small>Tại quầy &amp; Shipper giao xong</small>
            </div>
            <div class="step-count count-green">
              <strong>41</strong>
              <span>đơn</span>
            </div>
          </div>
        </div>

        <div class="workflow-footer">
          <span>Tỷ lệ hủy/đổi size tuần này: <strong>1.8%</strong></span>
          <a href="#" class="link-detail" @click.prevent="$emit('chuyen-tab', 'quan_ly_danh_gia')">Chi tiết khiếu nại</a>
        </div>
      </div>
    </div>

    <!-- HÀNG 3: TOP SẢN PHẨM BÁN CHẠY & ĐƠN MỚI TRỰC TIẾP -->
    <div class="bottom-grid">
      <!-- Bảng Top Sản phẩm -->
      <div class="content-panel panel-products">
        <div class="panel-header-line">
          <div>
            <h3 class="panel-heading">Top Sản Phẩm Bán Chạy Trong Tuần</h3>
            <span class="panel-subtext">Xếp hạng theo sản lượng và tỷ lệ hết size tại Flagship</span>
          </div>
          <a href="#" class="link-all-prod" @click.prevent="$emit('chuyen-tab', 'quan_ly_san_pham')">Xem danh mục sản phẩm &gt;</a>
        </div>

        <div class="table-wrap">
          <table class="atelier-table">
            <thead>
              <tr>
                <th>SẢN PHẨM &amp; MÃ SKU</th>
                <th width="90" class="text-center">ĐÃ BÁN</th>
                <th width="130">DOANH THU</th>
                <th width="160" class="text-center">TỒN KHO SIZE (S / M / L)</th>
              </tr>
            </thead>
            <tbody>
              <!-- Row 1 -->
              <tr>
                <td>
                  <div class="prod-item">
                    <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=120&q=80" alt="Blazer" class="prod-img" />
                    <div class="prod-info">
                      <strong class="prod-title">Blazer Linen Thắt Eo Cổ V</strong>
                      <span class="prod-sku">SKU: WA-B224-SND • Be cát tự nhiên</span>
                    </div>
                  </div>
                </td>
                <td class="text-center font-bold">38</td>
                <td><strong class="price-atelier">45.220.000đ</strong></td>
                <td class="text-center">
                  <div class="size-boxes">
                    <span class="size-pill pill-red">S: 8</span>
                    <span class="size-pill pill-blue">M: 4</span>
                    <span class="size-pill pill-blue">L: 9</span>
                  </div>
                </td>
              </tr>

              <!-- Row 2 -->
              <tr>
                <td>
                  <div class="prod-item">
                    <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=120&q=80" alt="Maxi" class="prod-img" />
                    <div class="prod-info">
                      <strong class="prod-title">Đầm Maxi Lụa Satin Xẻ Tà</strong>
                      <span class="prod-sku">SKU: WA-DR18-NOIR • Đen Noir óng</span>
                    </div>
                  </div>
                </td>
                <td class="text-center font-bold">29</td>
                <td><strong class="price-atelier">43.210.000đ</strong></td>
                <td class="text-center">
                  <div class="size-boxes">
                    <span class="size-pill pill-red">S: 3</span>
                    <span class="size-pill pill-blue">M: 8</span>
                    <span class="size-pill pill-blue">L: 6</span>
                  </div>
                </td>
              </tr>

              <!-- Row 3 -->
              <tr>
                <td>
                  <div class="prod-item">
                    <img src="https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=120&q=80" alt="Sơ mi" class="prod-img" />
                    <div class="prod-info">
                      <strong class="prod-title">Sơ mi Poplin Dáng Rộng Oversize</strong>
                      <span class="prod-sku">SKU: WA-SH07-WHT • Trắng tinh khiết</span>
                    </div>
                  </div>
                </td>
                <td class="text-center font-bold">24</td>
                <td><strong class="price-atelier">21.360.000đ</strong></td>
                <td class="text-center">
                  <div class="size-boxes">
                    <span class="size-pill pill-blue">S: 12</span>
                    <span class="size-pill pill-blue">M: 15</span>
                    <span class="size-pill pill-blue">L: 8</span>
                  </div>
                </td>
              </tr>

              <!-- Row 4 -->
              <tr>
                <td>
                  <div class="prod-item">
                    <img src="https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=120&q=80" alt="Quần tây" class="prod-img" />
                    <div class="prod-info">
                      <strong class="prod-title">Quần Tây Xếp Ly Ống Rộng Cao Cấp</strong>
                      <span class="prod-sku">SKU: WA-TR09-OLV • Xanh Olive Trầm</span>
                    </div>
                  </div>
                </td>
                <td class="text-center font-bold">21</td>
                <td><strong class="price-atelier">22.890.000đ</strong></td>
                <td class="text-center">
                  <div class="size-boxes">
                    <span class="size-pill pill-red">S: 2</span>
                    <span class="size-pill pill-red">M: 9</span>
                    <span class="size-pill pill-blue">L: 4</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Feed Đơn Mới Trực Tiếp -->
      <div class="content-panel panel-feed">
        <div class="panel-header-line">
          <h3 class="panel-heading"><span class="dot-live-green">●</span> Đơn Mới Trực Tiếp</h3>
          <span class="live-pos-label">LIVE POS</span>
        </div>

        <div class="order-feed-list">
          <!-- Item 1 -->
          <div class="feed-item">
            <div class="feed-header">
              <span class="feed-id">#ORD-5821</span>
              <span class="feed-time">Vừa xong (15:39)</span>
            </div>
            <div class="feed-main">
              <div class="feed-customer">
                <strong>Chị Thảo My (VIP)</strong>
                <small>2 món • Blazer Linen Be (M), Khăn lụa...</small>
              </div>
              <div class="feed-amount">
                <span class="feed-price">2.280.000đ</span>
                <span class="feed-pay pay-vnpay">VNPAY QR</span>
              </div>
            </div>
            <div class="feed-actions">
              <span class="feed-badge-quầy">Tại quầy Flagship</span>
              <button type="button" class="btn-feed-action" @click="inBill('#ORD-5821')">🖨️ In bill</button>
            </div>
          </div>

          <!-- Item 2 -->
          <div class="feed-item">
            <div class="feed-header">
              <span class="feed-id">#ORD-5820</span>
              <span class="feed-time">4 phút trước</span>
            </div>
            <div class="feed-main">
              <div class="feed-customer">
                <strong>Khách vãng lai - Quầy 2</strong>
                <small>1 món • Đầm Maxi Lụa Satin (S)</small>
              </div>
              <div class="feed-amount">
                <span class="feed-price">1.490.000đ</span>
                <span class="feed-pay pay-card">Thẻ Visa</span>
              </div>
            </div>
            <div class="feed-actions">
              <span class="feed-badge-quầy">Tại quầy Flagship</span>
              <button type="button" class="btn-feed-action" @click="inBill('#ORD-5820')">🖨️ In bill</button>
            </div>
          </div>

          <!-- Item 3 -->
          <div class="feed-item">
            <div class="feed-header">
              <span class="feed-id">#ORD-5819</span>
              <span class="feed-time">12 phút trước</span>
            </div>
            <div class="feed-main">
              <div class="feed-customer">
                <strong>Instagram @helen.tran</strong>
                <small>1 món • Sơ mi Poplin White (M)</small>
              </div>
              <div class="feed-amount">
                <span class="feed-price">890.000đ</span>
                <span class="feed-pay pay-cod">COD Hỏa tốc</span>
              </div>
            </div>
            <div class="feed-actions">
              <span class="feed-badge-ship">AhaMove lấy hàng</span>
              <button type="button" class="btn-feed-action" @click="inBill('#ORD-5819')">📦 Phiếu giao</button>
            </div>
          </div>

          <!-- Item 4 -->
          <div class="feed-item">
            <div class="feed-header">
              <span class="feed-id">#ORD-5818</span>
              <span class="feed-time">22 phút trước</span>
            </div>
            <div class="feed-main">
              <div class="feed-customer">
                <strong>Chị Lan Hương (Khách quen)</strong>
                <small>3 món • Phụ kiện thắt lưng, Áo lụa...</small>
              </div>
              <div class="feed-amount">
                <span class="feed-price">1.820.000đ</span>
                <span class="feed-pay pay-cash">Tiền mặt</span>
              </div>
            </div>
            <div class="feed-actions">
              <span class="feed-badge-quầy">Tại quầy Flagship</span>
              <button type="button" class="btn-feed-action" @click="inBill('#ORD-5818')">🖨️ In bill</button>
            </div>
          </div>
        </div>

        <a href="#" class="view-all-orders" @click.prevent="$emit('chuyen-tab', 'quan_ly_don_hang')">Xem toàn bộ 86 hóa đơn trong ngày →</a>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const emit = defineEmits(['chuyen-tab'])

const thoiGianHienTai = ref('hom_nay')

const thoiGianLabel = computed(() => {
  if (thoiGianHienTai.value === '7_ngay') return '7 ngày qua'
  if (thoiGianHienTai.value === 'thang_nay') return 'Tháng này'
  return 'Hôm nay'
})

const kpiData = computed(() => {
  if (thoiGianHienTai.value === '7_ngay') {
    return {
      doanhThu: '312.450.000',
      donHang: '580',
      donTaiQuay: '340',
      donOnline: '240',
      sanPham: '942'
    }
  }
  if (thoiGianHienTai.value === 'thang_nay') {
    return {
      doanhThu: '1.240.800.000',
      donHang: '2.150',
      donTaiQuay: '1.280',
      donOnline: '870',
      sanPham: '3.450'
    }
  }
  return {
    doanhThu: '48.650.000',
    donHang: '86',
    donTaiQuay: '52',
    donOnline: '34',
    sanPham: '142'
  }
})

function chonThoiGian(moc) {
  thoiGianHienTai.value = moc
}

function xuatBaoCao() {
  alert('Đang kết xuất báo cáo doanh số ' + thoiGianLabel.value + ' ra file Excel & PDF thành công!')
}

function xemChiTietDoanhThu() {
  alert('Chi tiết doanh thu ' + thoiGianLabel.value + ': Đạt ' + kpiData.value.doanhThu + 'đ (Tăng trưởng 14.2% so với kỳ trước).')
}

function inBill(maDon) {
  alert('Lệnh in hóa đơn ' + maDon + ' đã được gửi tới máy in nhiệt POS Flagship Q1!')
}
</script>

<style scoped>
.clickable-card {
  cursor: pointer;
  transition: all 0.2s ease;
}
.clickable-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.maison-dashboard {
  padding: 24px 30px;
  background-color: #f6f7f9;
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  color: #1e293b;
}

/* HEADER */
.dash-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}
.breadcrumb-meta {
  font-size: 11px;
  font-weight: 700;
  color: #64748b;
  letter-spacing: 0.6px;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 4px;
}
.pos-sync-tag {
  background: #e0f2fe;
  color: #0284c7;
  padding: 2px 7px;
  border-radius: 4px;
  font-size: 10px;
}
.greeting-title {
  margin: 0;
  font-size: 24px;
  font-weight: 800;
  color: #0f172a;
}
.greeting-subtitle {
  margin: 4px 0 0;
  font-size: 13px;
  color: #64748b;
}

.dash-controls {
  display: flex;
  align-items: center;
  gap: 10px;
}
.time-pill-group {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 3px;
  display: flex;
}
.btn-time {
  border: none;
  background: transparent;
  padding: 6px 12px;
  font-size: 12px;
  font-weight: 500;
  color: #64748b;
  border-radius: 6px;
  cursor: pointer;
}
.btn-time.active {
  background: #0f172a;
  color: #ffffff;
  font-weight: 600;
}
.btn-report {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 12.5px;
  font-weight: 600;
  color: #334155;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
}
.btn-new-order {
  background: #0f172a;
  color: #ffffff;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
}
.btn-new-order .shortcut {
  background: #334155;
  font-size: 10px;
  padding: 1px 5px;
  border-radius: 4px;
}

/* 4 CARD KPI */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}
@media (max-width: 1100px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
}

.kpi-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 18px 20px;
  border: 1px solid #eef0f3;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.kpi-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}
.kpi-label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.5px;
  color: #64748b;
}
.kpi-icon-box {
  font-size: 14px;
  width: 26px;
  height: 26px;
  background: #f1f5f9;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.kpi-body {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  margin-bottom: 6px;
}
.kpi-val-wrap {
  display: flex;
  align-items: baseline;
  gap: 4px;
}
.kpi-number {
  margin: 0;
  font-size: 26px;
  font-weight: 800;
  color: #0f172a;
  letter-spacing: -0.5px;
}
.kpi-unit {
  font-size: 14px;
  color: #64748b;
  font-weight: 600;
}
.kpi-sparkline {
  width: 70px;
  height: 24px;
}
.sparkline-svg {
  width: 100%;
  height: 100%;
}
.kpi-footer {
  font-size: 11.5px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.trend-up {
  color: #16a34a;
  font-weight: 700;
}
.trend-compare {
  color: #94a3b8;
}
.text-muted-meta {
  color: #64748b;
  font-size: 11px;
}
.dot-sep { color: #cbd5e1; }
.tag-top-cat {
  color: #0284c7;
  background: #e0f2fe;
  padding: 2px 7px;
  border-radius: 4px;
  font-size: 11px;
  font-weight: 500;
}
.kpi-warning {
  border-color: #fee2e2;
  background: #fffafa;
}
.box-danger { background: #fee2e2; }
.text-danger { color: #dc2626 !important; }
.link-restock {
  color: #dc2626;
  text-decoration: none;
  font-size: 11px;
  font-weight: 600;
}
.link-restock:hover { text-decoration: underline; }

/* HÀNG 2: MID-GRID */
.mid-grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr;
  gap: 16px;
  margin-bottom: 24px;
}
@media (max-width: 1024px) {
  .mid-grid { grid-template-columns: 1fr; }
}

.content-panel {
  background: #ffffff;
  border-radius: 12px;
  padding: 20px;
  border: 1px solid #eef0f3;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.panel-header-line {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 4px;
}
.panel-heading {
  margin: 0;
  font-size: 15px;
  font-weight: 700;
  color: #0f172a;
}
.panel-subtext {
  font-size: 11.5px;
  color: #94a3b8;
}

.chart-legend {
  display: flex;
  gap: 12px;
  font-size: 11px;
  color: #64748b;
}
.legend-item { display: flex; align-items: center; gap: 5px; }
.legend-box { width: 8px; height: 8px; border-radius: 2px; }
.box-black { background: #0f172a; }
.box-orange { background: #f97316; }

/* BAR CHART */
.bar-chart-container {
  height: 180px;
  margin: 24px 0 16px;
  display: flex;
  align-items: flex-end;
  border-bottom: 1px solid #f1f5f9;
  padding-bottom: 4px;
}
.chart-bars {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 8px;
}
.bar-col {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 100%;
  justify-content: flex-end;
  position: relative;
}
.bar-fill {
  width: 22px;
  background: #e2e8f0;
  border-radius: 4px 4px 0 0;
  transition: all 0.2s;
}
.bar-fill:hover { background: #cbd5e1; }
.bar-peak {
  background: #0f172a !important;
}
.bar-time {
  font-size: 10px;
  color: #94a3b8;
  margin-top: 8px;
}
.col-peak {
  position: relative;
}
.peak-tooltip {
  position: absolute;
  top: 0;
  background: #0f172a;
  color: #ffffff;
  font-size: 10px;
  font-weight: 700;
  padding: 3px 6px;
  border-radius: 4px;
  white-space: nowrap;
}

/* 4 THANH TIẾN TRÌNH */
.category-breakdown {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-top: 18px;
}
.cat-progress-card {
  background: #f8fafc;
  padding: 10px;
  border-radius: 8px;
}
.cat-meta {
  display: flex;
  justify-content: space-between;
  font-size: 10.5px;
  margin-bottom: 6px;
}
.cat-name { color: #64748b; font-weight: 600; }
.cat-pct { color: #0f172a; }
.progress-bar {
  height: 4px;
  background: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
}
.progress-fill { height: 100%; }
.fill-blue { background: #3b82f6; }
.fill-orange { background: #f97316; }
.fill-green { background: #10b981; }
.fill-gray { background: #94a3b8; }

/* VẬN HÀNH ĐƠN HÀNG */
.order-total-badge {
  font-size: 11px;
  background: #e0f2fe;
  color: #0369a1;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 12px;
}
.workflow-steps {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 14px;
}
.step-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 14px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #f1f5f9;
}
.step-num {
  font-size: 12px;
  font-weight: 800;
  color: #0284c7;
  width: 26px;
  height: 26px;
  background: #e0f2fe;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.step-done .step-num {
  background: #dcfce7;
  color: #16a34a;
}
.step-content {
  flex: 1;
}
.step-content strong {
  display: block;
  font-size: 12.5px;
  color: #0f172a;
}
.step-content small {
  color: #94a3b8;
  font-size: 11px;
}
.step-count {
  text-align: right;
  line-height: 1.1;
}
.step-count strong {
  font-size: 15px;
  color: #0f172a;
}
.step-count span {
  display: block;
  font-size: 10px;
  color: #94a3b8;
}
.count-highlight strong { color: #f97316; }
.count-green strong { color: #16a34a; }

.workflow-footer {
  font-size: 11.5px;
  color: #64748b;
  display: flex;
  justify-content: space-between;
  border-top: 1px solid #f1f5f9;
  padding-top: 10px;
}
.link-detail {
  color: #2563eb;
  text-decoration: none;
}

/* HÀNG 3: BOTTOM GRID */
.bottom-grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr;
  gap: 16px;
}
@media (max-width: 1024px) {
  .bottom-grid { grid-template-columns: 1fr; }
}

.link-all-prod {
  font-size: 12px;
  color: #475569;
  text-decoration: none;
  font-weight: 500;
}
.link-all-prod:hover { text-decoration: underline; }

.atelier-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
  margin-top: 14px;
}
.atelier-table th {
  font-size: 10.5px;
  font-weight: 700;
  color: #64748b;
  border-bottom: 1px solid #f1f5f9;
  padding: 8px 10px;
  text-align: left;
}
.atelier-table td {
  padding: 10px;
  border-bottom: 1px solid #f8fafc;
  vertical-align: middle;
}

.prod-item {
  display: flex;
  align-items: center;
  gap: 10px;
}
.prod-img {
  width: 38px;
  height: 38px;
  border-radius: 6px;
  object-fit: cover;
}
.prod-info {
  display: flex;
  flex-direction: column;
}
.prod-title {
  font-size: 13px;
  color: #0f172a;
}
.prod-sku {
  font-size: 10.5px;
  color: #94a3b8;
}
.price-atelier {
  font-size: 13px;
  color: #0f172a;
}

.size-boxes {
  display: inline-flex;
  gap: 4px;
}
.size-pill {
  font-size: 10px;
  font-weight: 700;
  padding: 2px 5px;
  border-radius: 4px;
}
.pill-red {
  background: #fee2e2;
  color: #dc2626;
}
.pill-blue {
  background: #e0f2fe;
  color: #0369a1;
}

/* FEED ĐƠN TRỰC TIẾP */
.dot-live-green {
  color: #10b981;
  font-size: 12px;
  margin-right: 4px;
}
.live-pos-label {
  font-size: 9.5px;
  font-weight: 800;
  background: #f1f5f9;
  color: #475569;
  padding: 2px 6px;
  border-radius: 4px;
}
.order-feed-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 14px;
}
.feed-item {
  background: #f8fafc;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #f1f5f9;
}
.feed-header {
  display: flex;
  justify-content: space-between;
  font-size: 11px;
  margin-bottom: 4px;
}
.feed-id {
  font-family: monospace;
  font-weight: 700;
  color: #0f172a;
}
.feed-time {
  color: #94a3b8;
}
.feed-main {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  margin-bottom: 6px;
}
.feed-customer strong {
  display: block;
  font-size: 12.5px;
  color: #0f172a;
}
.feed-customer small {
  font-size: 11px;
  color: #64748b;
}
.feed-amount {
  text-align: right;
}
.feed-price {
  font-size: 13px;
  font-weight: 800;
  color: #0f172a;
  display: block;
}
.feed-pay {
  font-size: 10px;
  font-weight: 600;
  padding: 1px 5px;
  border-radius: 3px;
}
.pay-vnpay { background: #dcfce7; color: #15803d; }
.pay-card { background: #e0e7ff; color: #4338ca; }
.pay-cod { background: #fef3c7; color: #b45309; }
.pay-cash { background: #f1f5f9; color: #475569; }

.feed-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 11px;
}
.feed-badge-quầy { color: #64748b; }
.feed-badge-ship { color: #f97316; font-weight: 600; }
.btn-feed-action {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  padding: 3px 8px;
  border-radius: 4px;
  font-size: 11px;
  cursor: pointer;
  color: #334155;
}

.view-all-orders {
  display: block;
  text-align: center;
  font-size: 12px;
  color: #475569;
  text-decoration: none;
  margin-top: 12px;
  font-weight: 600;
}
.view-all-orders:hover { color: #0f172a; }

.font-bold { font-weight: 700; }
.text-center { text-align: center; }
</style>
