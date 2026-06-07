@extends('layout')

@section('header_actions')
    <a class="primary-button" href="/app/login">Login</a>
@endsection

@section('content')
    <section class="hero">
        <div class="content">
            <span class="eyebrow">Graduate School</span>
            <h1>Grading Sheet Submission and Tracking System</h1>
            <p>
                The official portal for the submission and management of grading sheets for the Graduate School of St. Paul University Philippines. This system provides graduate school faculty members with a secure, efficient, and centralized platform for encoding, reviewing, and submitting student grades online. Designed to streamline academic processes, the portal ensures accurate record-keeping, timely submission of grades, and convenient access for authorized users while supporting the university's commitment to academic excellence, innovation, and quality service in graduate education.
            </p>
            <hr class="divider">
            <div class="steps">
                <div class="step-card">
                    <strong>Submit Grading Sheets</strong>
                    <p>Upload grading sheets and confirm required fields to begin.</p>
                </div>
                <div class="step-card">
                    <strong>Monitor Submissions</strong>
                    <p>Review course details and ensure the academic year is correct.</p>
                </div>
                <div class="step-card">
                    <strong>Track Approvals</strong>
                    <p>Monitor status updates as submissions move to approval.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <footer class="site-footer">
        <div class="footer-inner">

            <!-- Brand / About -->
            <div class="footer-brand">
                <img src="images/sys-spup.png" alt="SPUP Logo">
                <p>
                    The Grading Sheet Submission Portal is an official internal system of the
                    Graduate School, St. Paul University Philippines. Designed to streamline
                    faculty submissions and academic record management.
                </p>
            </div>

            <!-- Dean's Office -->
            <div class="footer-col">
                <h4>Dean's Office</h4>
                <ul>
                    <li>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Dr. Teoticia Taguibao<br><span style="color:rgba(255,255,255,0.45);font-size:12px;">Acting Dean, Graduate School</span></span>
                    </li>
                    <li>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Ground Floor, Our Lady of Chartres (OLC) Building</span>
                    </li>
                    <li>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V6a2 2 0 012-2z"/></svg>
                        <span>(078) 396-1987 to 1997 loc. 214</span>
                    </li>
                </ul>
            </div>

            <!-- University Info -->
            <div class="footer-col">
                <h4>University</h4>
                <ul>
                    <li>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>St. Paul University Philippines</span>
                    </li>
                    <li>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Mabini Street, Tuguegarao City, 3500 Cagayan, Philippines</span>
                    </li>
                    <li>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <a href="https://spup.edu.ph" target="_blank">www.spup.edu.ph</a>
                    </li>
                    <li>
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V6a2 2 0 012-2z"/></svg>
                        <span>(078) 396-1987 to 1997</span>
                    </li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} St. Paul University Philippines — Graduate School. All rights reserved.</p>
            <span class="footer-motto">Caritas · Veritas · Scientia</span>
        </div>
    </footer>
@endsection