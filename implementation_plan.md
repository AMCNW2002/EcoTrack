# Admin Waste Report Management System Implementation Plan

This document outlines the technical approach to implementing PART 05, specifically focusing on connecting the existing Citizen and Collector modules with the new Admin module using a unified PHP session state.

## User Review Required
> [!IMPORTANT]
> The End-to-End flow requires synchronizing the isolated mock data stores (`$_SESSION['reports']` and `$_SESSION['collections']`). I will update the action handlers in the Collector module to also update the corresponding Citizen report when a collection is completed, ensuring the Citizen sees the updated status. This modifies existing mock logic but preserves all UI/UX functionality. Please confirm this approach.

## Proposed Changes

### Core State Synchronization

#### [MODIFY] `collector/todays-collections.php` & `collector/collection-details.php`
- Update the AJAX endpoints that handle "Start Collection" and "Complete Collection" to also locate the associated `report_id` in `$_SESSION['reports']` and update its status to `In Progress` and `Collected` respectively.

#### [NEW] `admin/init.php`
- Will act as the central initializer for Admin. It will ensure `$_SESSION['reports']`, `$_SESSION['collections']`, and the new `$_SESSION['collectors']` (array of mock collector profiles) are generated and available.

### Admin Interface

#### [MODIFY] `includes/admin_sidebar.php`
- Update with the requested navigation structure (Waste Management, People, Operations, Management, Analytics).

#### [MODIFY] `admin/dashboard.php`
- Add the "Waste Management Overview" statistics cards.
- Add the "Recent Waste Reports" table fetching the 6 most recent items from `$_SESSION['reports']`.

#### [NEW] `admin/waste-reports.php`
- Build a comprehensive table/card view of all reports.
- Implement JavaScript-based search and multi-filtering (Status, Type, Priority, Area).

#### [NEW] `admin/report-details.php`
- Build the detailed view with citizen info, waste info, and a dynamic timeline.
- Implement AJAX handlers for Approve (changes status to `Approved`) and Reject (changes status to `Rejected`).

#### [NEW] `admin/assign-collector.php`
- Interface to select a collector from `$_SESSION['collectors']`.
- Triggers a "Schedule Collection" modal upon selection.
- Submitting the schedule will create a new entry in `$_SESSION['collections']` and update the report status to `Scheduled`.

#### [NEW] `admin/collection-management.php`
- Build a dashboard to view all records in `$_SESSION['collections']` with frontend filtering.

#### [NEW] `admin/collectors.php`
- Build a directory view of all available garbage collectors.

#### [NEW] `assets/css/admin.css` & `assets/js/admin.js`
- Create styles for the timeline, status badges, and JavaScript for AJAX form submissions and frontend filtering.

## Verification Plan

### Manual Verification
1. **End-to-End Test**:
   - Login as Citizen -> Create Report WR-TEST.
   - Login as Admin -> View WR-TEST -> Approve -> Assign Collector -> Schedule.
   - Login as Collector -> See WR-TEST in Today's Collections -> Start -> Complete.
   - Login as Citizen -> Verify WR-TEST is marked as "Collected/Resolved".
2. **Mobile UI Test**: Shrink browser to mobile width and verify tables collapse into cards on all new Admin pages.
3. **Session Stability**: Ensure session variables do not overwrite each other during navigation.
