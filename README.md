# Event Calendar Application

A responsive **Event Calendar** web application built with **Laravel**, **FullCalendar**, **jQuery**, and **Tailwind CSS**.  
It allows users to **create, view, edit, and delete events** with a calendar interface and a dynamic event list. The app supports **desktop and mobile views** and displays events in **Nepal Time (Asia/Kathmandu)**.

---

## Features

- **FullCalendar Integration**
  - Month, Week, Day, and List views
  - Mobile-responsive display
  - Event times shown as blue badges in Month/Week views
  - Proper 12-hour format with AM/PM

- **Event Management**
  - Add, edit, and delete events
  - Dynamic event list with title, date & time, email, and actions
  - AJAX-based deletion without page refresh
  - Confirmation before deleting an event

- **Responsive Design**
  - Desktop: Month view by default with view dropdown
  - Mobile: List view by default for readability
  - Tailwind CSS for modern UI

- **Timezone Support**
  - Converts all event times to **Nepal Time (Asia/Kathmandu)**

- **Queue Support (Optional)**
  - Laravel Jobs for background tasks like sending emails
  - Compatible with database or Redis queues

---

 <repository-url>
cd event-calendar
