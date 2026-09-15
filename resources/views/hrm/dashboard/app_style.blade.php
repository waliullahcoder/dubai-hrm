<style>
    /* Employee Mobile UI Wrapper */
    .app-container {
        max-width: 420px;
        margin: 0 auto;
        background: #f4f7fe;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        font-family: 'Inter', sans-serif;
    }

    /* Top Profile Header */
    .app-header {
        background: #0066ff;
        padding: 24px 20px 30px 20px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ffffff;
    }

    .user-info .greeting {
        font-size: 13px;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .user-info .name {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 0;
        line-height: 1.2;
    }

    .user-info .emp-id {
        font-size: 11px;
        opacity: 0.8;
    }

    .notification-btn {
        position: relative;
        background: rgba(255, 255, 255, 0.2);
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        text-decoration: none;
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: #ff3b30;
        color: white;
        font-size: 10px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border: 2px solid #0066ff;
    }

    /* Main Body Area */
    .app-body {
        padding: 0 16px;
        margin-top: -18px;
        padding-bottom: 70px;
    }

    /* Check-In Card Area */
    .checkin-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 16px;
    }

    .status-box {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .status-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e6f9f0;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .status-info h6 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
        font-size: 15px;
    }

    .status-info p {
        margin: 0;
        font-size: 12px;
        color: #64748b;
    }

    .btn-checkin {
        width: 100%;
        background: #00ab55;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 171, 85, 0.25);
    }

    .btn-checkin:hover {
        background: #009449;
        color: #fff;
    }


    .btn-checkout {
        width: 100%;
        background: #e0270e;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0, 171, 85, 0.25);
    }

    .btn-checkout:hover {
        background: #e0270e;
        color: #fff;
    }

    /* Menu Grid Cards */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .menu-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 15px;
        text-align: center;
        text-decoration: none;
        color: #1e293b;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .menu-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        color: #0066ff;
    }

    .menu-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #eef5ff;
        color: #0066ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .menu-title {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
    }

    /* Bottom Navigation Bar */
    .bottom-nav {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
        border-bottom-left-radius: 28px;
        border-bottom-right-radius: 28px;
    }

    .nav-item {
        text-decoration: none;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 600;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .nav-item i {
        font-size: 18px;
    }

    .nav-item.active {
        color: #0066ff;
    }
    .fa, .fab, .fad, .fal, .far, .fas{
     color: #56ff84 !important;
    }
    .menu-icon{
       background: #173b8f !important;
    }
</style>


 <style>
                    .status-box {
                        display: flex;
                        align-items: center;
                        gap: 15px;
                        padding: 18px;
                        margin: 15px 0;
                        border-radius: 18px;
                        background: linear-gradient(135deg, #0A0F2E, #16204A);
                        box-shadow: 0 8px 25px rgba(10, 15, 46, 0.18);
                        color: #fff;
                        position: relative;
                        overflow: hidden;
                    }

                    .status-box::after {
                        content: "";
                        position: absolute;
                        width: 100px;
                        height: 100px;
                        right: -40px;
                        top: -40px;
                        border-radius: 50%;
                        background: rgba(0, 255, 136, 0.08);
                    }

                    .status-icon {
                        width: 62px;
                        height: 62px;
                        min-width: 62px;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.12);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 2px solid rgba(255, 255, 255, 0.2);
                        margin-left:126px;
                    }

                    .status-icon i {
                        font-size: 30px;
                        color: #fff;
                    }

                    .status-info {
                        flex: 1;
                        position: relative;
                        z-index: 2;
                    }

                    .status-info h6 {
                        margin: 0 0 5px;
                        font-size: 18px;
                        font-weight: 700;
                        color: #27ff20;
                    }

                    .status-info .subtitle {
                        margin: 0 0 10px;
                        font-size: 13px;
                        color: rgba(255, 255, 255, 0.7);
                    }

                    .location-status {
                        display: flex;
                        align-items: center;
                        gap: 7px;
                        margin-bottom: 10px;
                        padding: 8px 10px;
                        border-radius: 10px;
                        background: rgba(0, 255, 136, 0.1);
                        color: #00ff88;
                        font-size: 12px;
                        font-weight: 600;
                    }

                    .location-status i {
                        font-size: 14px;
                    }

                    .checkin-time {
                        /* display: flex;
                        text-align:center;
                        align-items: center; */
                        gap: 8px;
                        padding-top: 9px;
                        border-top: 1px solid rgba(255, 255, 255, 0.12);
                        font-size: 13px;
                        color: #fff;
                    }

                    .checkin-time i {
                        color: #00ff88;
                        font-size: 16px;
                    }

                    .checkin-time strong {
                        color: #00ff88;
                    }

                    @media (max-width: 480px) {
                        .status-box {
                            padding: 16px;
                            gap: 12px;
                            border-radius: 16px;
                        }

                        .status-icon {
                            width: 55px;
                            height: 55px;
                            min-width: 55px;
                        }

                        .status-icon i {
                            font-size: 26px;
                        }

                        .status-info h6 {
                            font-size: 16px;
                        }
                    }
                </style>

                
<style>
    .attendance-table {
        width: 100%;
        border: none !important;
        border-collapse: collapse;
    }

    .attendance-table tr,
    .attendance-table td {
        border: none !important;
    }

    .attendance-table td {
        padding: 5px 0;
        font-size: 13px;
        color: #ffffff;
    }

    .attendance-table td:first-child {
        text-align: left;
        color: rgba(255, 255, 255, 0.7);
    }

    .attendance-table td:last-child {
        text-align: right;
        color: #ffffff;
    }

    .attendance-table strong {
        color: #00ff88;
        font-weight: 600;
    }
    .checktime{
        font-size:2em;
        color:#1cb174 !important;
    }
</style>