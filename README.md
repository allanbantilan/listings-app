# Listings App

This is a **Listings App** built with **Laravel 11**, **Vue 3**, and **Inertia.js**. The application allows users to create and manage listings, with an approval system managed by the admin. The app features user roles, with separate dashboards for users and admins.

## Features

- **User Registration and Authentication**: Users can register, log in, and manage their accounts.
- **Post Listings**: Users can create new listings.
- **Admin Approval**: Listings must be approved by an admin before being published.
- **Admin Dashboard**: Admins can view, approve, and suspend listings and users.
- **User Dashboard**: Users can manage their listings and see their status (approved, pending, or rejected).
- **Admin Control**: Admins can suspend users from posting new listings.
  
## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Vue 3
- **SPA**: Inertia.js
- **Database**: SQlite
  
## Features

### Admin Features

- **View All User Listings**: Admins can see a list of all user-submitted listings.
- **Approve or Reject Listings**: Admins can approve listings to make them visible to the public or reject them.
- **Suspend Users**: Admins have the ability to suspend users from posting new listings.
- **Manage Users**: Admins can view user details and suspend them if necessary.

### User Features

- **Post Listings**: Users can submit new listings, which must be approved by an admin before they are visible.
- **Manage Listings**: Users can view and manage the listings they have posted.
- **Listing Status**: Users can see the status of their listings (approved, pending approval, or rejected).

