
# Implementation Plan - Device Area Transfer Feature

The user wants to implement a "Data Transfer" feature for Devices, specifically clarifying that its purpose (or a key part of it) is to **change the Organization/Area** of the selected devices.

## User Requirements
- "tambahkan fitur ini di devices, fitur ini aktif ketika salah satu list device nya di ceklist" -> Add this feature, active on checkbox selection (Bulk Action).
- "maksud dari data transfer ini itu ubah data organisasinya/area" -> The meaning/purpose is to change Organization/Area data.

## Proposed Changes

### 1. Modify `DevicesTable.php`
We will update the `bulkActions` in `app/Filament/Hris/Resources/Devices/Tables/DevicesTable.php`.

- **Update "Data Transfer" Group**:
    - We will keep the existing "Upload/Sync" actions if they are standard, but we will **ADD** (or highlight) a **"Transfer Area"** (Change Area) action.
    - **Action Name**: `transfer_area` or `change_area`.
    - **Label**: "Transfer Area" / "Move to Area".
    - **Form**:
        - A `Select` field for `department_id` (Area).
        - Options should list available Departments (Areas).
    - **Logic**:
        - On submit, update the `department_id` of all selected `Device` records.
        - Show a success notification "Devices transferred to [New Area]".

- **Cleanup**: Remove unused imports flagged by linter in previous step.

## Verification
- Run `php artisan test` or manual verification if tests are not set up for this specific UI action.
- Ensure the bulk action appears when devices are selected.
- Ensure the modal opens and lists areas.
- Ensure the update persists to the database.
