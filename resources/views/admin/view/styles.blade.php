<style>
    :root {
        --accent: #03522a;
        --accent-light: rgba(3, 82, 42, 0.08);
        --accent-medium: rgba(3, 82, 42, 0.15);
    }

    /* ── Profile Card ── */
    .profile-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid rgba(0, 0, 0, 0.07);
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        overflow: hidden;
        position: sticky;
        top: 1.5rem;
    }

    .profile-banner {
        height: 72px;
        background: #03522a;
    }

    .profile-body {
        padding: 0 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profile-avatar-wrapper {
        margin-top: -2.75rem;
        margin-bottom: 1rem;
    }

    .profile-avatar-gradient {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        border: 3px solid white;
        box-shadow: 0 4px 10px rgba(3, 82, 42, 0.25);
    }

    .profile-avatar-img {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: rgb(17, 24, 39);
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .profile-role-badge {
        display: inline-block;
        font-size: 0.6875rem;
        font-weight: 600;
        background: var(--accent-light);
        color: var(--accent);
        padding: 0.2rem 0.65rem;
        border-radius: 9999px;
        margin-bottom: 1.25rem;
        letter-spacing: 0.01em;
    }

    .profile-details-list {
        width: 100%;
        border-top: 1px solid rgba(0,0,0,0.06);
        padding-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        text-align: left;
    }

    .profile-detail-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.8125rem;
        color: rgb(75, 85, 99);
    }

    .profile-detail-icon {
        width: 1rem;
        height: 1rem;
        color: var(--accent);
        flex-shrink: 0;
        opacity: 0.8;
    }

    .profile-detail-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ── Merged Card Divider & Details ── */
    .card-divider {
        width: 100%;
        height: 1px;
        background: rgba(0, 0, 0, 0.06);
        margin: 1.25rem 0;
    }

    .merged-details-title {
        width: 100%;
        font-size: 0.75rem;
        font-weight: 700;
        margin-top: 0;
        margin-bottom: 0.875rem;
        text-align: left;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--accent);
    }

    .merged-details-list {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        text-align: left;
    }

    .merged-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        font-size: 0.8125rem;
        gap: 1rem;
        width: 100%;
    }

    .merged-detail-label {
        font-weight: 600;
        color: rgb(107, 114, 128);
        flex-shrink: 0;
    }

    .merged-detail-val {
        color: rgb(17, 24, 39);
        text-align: right;
        word-break: break-word;
    }

    /* ── Status Badges ── */
    .status-badge {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.2rem 0.65rem;
        border-radius: 9999px;
    }

    .status-pending {
        background: rgba(107, 114, 128, 0.08);
        color: #6b7280;
    }

    .status-to_endorse {
        background: rgba(59, 130, 246, 0.08);
        color: #3b82f6;
    }

    .status-to_verify {
        background: rgba(245, 158, 11, 0.08);
        color: #f59e0b;
    }

    .status-submitted {
        background: rgba(16, 185, 129, 0.08);
        color: #10b981;
    }
</style>
