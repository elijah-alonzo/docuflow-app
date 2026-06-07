<style>
    :root {
        --accent: #03522a;
        --accent-light: rgba(3, 82, 42, 0.08);
        --accent-medium: rgba(3, 82, 42, 0.15);
    }

    /* ── Layout ── */
    .home-dashboard-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        width: 100%;
    }

    @media (min-width: 1024px) {
        .home-dashboard-container {
            grid-template-columns: 320px 1fr;
            align-items: start;
        }
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
        margin-bottom: 1.25rem;
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

    /* ── Right Column ── */
    .list-section-col {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        min-width: 0;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .section-header-icon {
        width: 1.5rem;
        height: 1.5rem;
        color: var(--accent);
        flex-shrink: 0;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: rgb(17, 24, 39);
    }

    /* ── Grading Sheet Cards ── */
    .grading-sheets-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .grading-sheet-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid rgba(0, 0, 0, 0.07);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.15s ease, transform 0.15s ease;
    }

    .grading-sheet-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(3, 82, 42, 0.08), 0 1px 3px rgba(0,0,0,0.06);
    }

    .grading-sheet-card-header {
        padding: 0.875rem 1.125rem;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        border-left: 3px solid var(--accent);
    }

    .grading-sheet-card-body {
        padding: 0.875rem 1.125rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        background: rgba(249, 250, 251, 0.6);
        flex-grow: 1;
        min-width: 0;
        overflow: hidden;
    }

    .grading-sheet-card-footer {
        padding: 0.75rem 1.125rem;
        background: rgba(249, 250, 251, 0.6);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: flex-end;
    }

    /* ── Buttons ── */
    .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.825rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-icon {
        width: 1rem;
        height: 1rem;
    }

    .upload-btn {
        background: var(--accent);
        color: white;
        box-shadow: 0 1px 2px rgba(3, 82, 42, 0.35);
    }

    .upload-btn:hover {
        background: #facc15;
        box-shadow: 0 2px 6px rgba(3, 82, 42, 0.4);
    }

    .view-btn {
        background: white;
        border: 1px solid rgb(229, 231, 235);
        color: rgb(75, 85, 99);
    }

    .view-btn:hover {
        background: rgb(249, 250, 251);
        color: rgb(17, 24, 39);
        border-color: rgb(209, 213, 219);
    }

    .profile-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.825rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        text-decoration: none;
        width: 100%;
        background: white;
        border: 1px solid rgb(229, 231, 235);
        color: rgb(75, 85, 99);
        box-sizing: border-box;
    }

    .profile-edit-btn:hover {
        background: var(--accent-light);
        color: var(--accent);
        border-color: rgba(3, 82, 42, 0.2);
    }

    /* ── Profile Actions ── */
    .profile-actions {
        width: 100%;
        margin-top: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .profile-actions form {
        width: 100%;
    }

    .logout-btn {
        color: rgb(220, 38, 38);
        border-color: rgba(220, 38, 38, 0.2);
    }

    .logout-btn:hover {
        background: rgba(220, 38, 38, 0.05);
        color: rgb(185, 28, 28);
        border-color: rgba(220, 38, 38, 0.35);
    }

    /* ── Empty State ── */
    .empty-state {
        background: white;
        border: 1px dashed rgba(0, 0, 0, 0.12);
        border-radius: 1rem;
        padding: 4rem 2rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .empty-state-content {
        max-width: 420px;
        text-align: center;
    }

    .empty-state-icon-wrapper {
        width: 72px;
        height: 72px;
        margin: 0 auto;
        border-radius: 20px;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon {
        width: 36px;
        height: 36px;
        color: var(--accent);
    }

    .empty-state-title {
        margin: 1.25rem 0 0.5rem;
        font-size: 1.125rem;
        font-weight: 600;
        color: rgb(17, 24, 39);
    }

    .empty-state-description {
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.6;
        color: rgb(107, 114, 128);
    }

    /* ── Status Tracker Overflow Fix ── */
    .grading-sheet-card-body .fi-sc-wizard {
        min-width: 0;
        width: 100%;
        overflow: hidden;
    }

    .grading-sheet-card-body .fi-sc-wizard-header {
        min-width: 0;
        width: 100%;
        display: flex;
        overflow: hidden;
    }

    .grading-sheet-card-body .fi-sc-wizard-header-step {
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .grading-sheet-card-body .fi-sc-wizard-header-step-btn {
        min-width: 0;
        width: 100%;
    }

    .grading-sheet-card-body .fi-sc-wizard-header-step-text {
        min-width: 0;
        overflow: hidden;
    }

    .grading-sheet-card-body .fi-sc-wizard-header-step-label {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .grading-sheet-card-body .fi-sc-wizard-header-step-description {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.7rem;
        color: rgb(107, 114, 128);
        margin-top: 0.125rem;
    }

/* ── Card Header Actions ── */
.grading-sheet-card-header-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
    flex-shrink: 0;
}
</style>