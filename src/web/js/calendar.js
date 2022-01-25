document.addEventListener("DOMContentLoaded", function () {

    var calendar = new tui.Calendar('#calendar', {
        usageStatistics: false,
        defaultView: 'week',
        //taskView: true,
    });

    function init() {
        //calendar.setCalendars(CalendarList);

        setRenderRangeText();
        //setSchedules();
        setEventListener();
    }

    function onClickMenu(e) {
        var target = $(e.target).closest('a[role="menuitem"]')[0];
        var action = getDataAction(target);
        var options = calendar.getOptions();
        var viewName = '';

        console.log(target);
        console.log(action);
        switch (action) {
            case 'toggle-daily':
                viewName = 'day';
                break;
            case 'toggle-weekly':
                viewName = 'week';
                break;
            case 'toggle-monthly':
                options.month.visibleWeeksCount = 0;
                viewName = 'month';
                break;
            case 'toggle-weeks2':
                options.month.visibleWeeksCount = 2;
                viewName = 'month';
                break;
            case 'toggle-weeks3':
                options.month.visibleWeeksCount = 3;
                viewName = 'month';
                break;
            case 'toggle-narrow-weekend':
                options.month.narrowWeekend = !options.month.narrowWeekend;
                options.week.narrowWeekend = !options.week.narrowWeekend;
                viewName = calendar.getViewName();

                target.querySelector('input').checked = options.month.narrowWeekend;
                break;
            case 'toggle-start-day-1':
                options.month.startDayOfWeek = options.month.startDayOfWeek ? 0 : 1;
                options.week.startDayOfWeek = options.week.startDayOfWeek ? 0 : 1;
                viewName = calendar.getViewName();

                target.querySelector('input').checked = options.month.startDayOfWeek;
                break;
            case 'toggle-workweek':
                options.month.workweek = !options.month.workweek;
                options.week.workweek = !options.week.workweek;
                viewName = calendar.getViewName();

                target.querySelector('input').checked = !options.month.workweek;
                break;
            default:
                break;
        }

        calendar.setOptions(options, true);
        calendar.changeView(viewName, true);

        setDropdownCalendarType();
        setRenderRangeText();
        //setSchedules();
    }

    function setDropdownCalendarType() {
        var calendarTypeName = document.getElementById('calendarTypeName');
        var calendarTypeIcon = document.getElementById('calendarTypeIcon');
        var options = calendar.getOptions();
        var type = calendar.getViewName();
        var iconClassName;

        if (type === 'day') {
            type = 'Daily';
            iconClassName = 'calendar-icon ic_view_day';
        } else if (type === 'week') {
            type = 'Weekly';
            iconClassName = 'calendar-icon ic_view_week';
        } else if (options.month.visibleWeeksCount === 2) {
            type = '2 weeks';
            iconClassName = 'calendar-icon ic_view_week';
        } else if (options.month.visibleWeeksCount === 3) {
            type = '3 weeks';
            iconClassName = 'calendar-icon ic_view_week';
        } else {
            type = 'Monthly';
            iconClassName = 'calendar-icon ic_view_month';
        }

        calendarTypeName.innerHTML = type;
        calendarTypeIcon.className = iconClassName;
    }

    function onClickNavi(e) {
        const action = getDataAction(e.target);

        switch (action) {
            case 'move-prev':
                calendar.prev();
                break;
            case 'move-next':
                calendar.next();
                break;
            case 'move-today':
                calendar.today();
                break;
            default:
                return;
        }

        setRenderRangeText();
    }

    function getDataAction(target) {
        return target.dataset ? target.dataset.action : target.getAttribute('data-action');
    }

    function setRenderRangeText() {
        var renderRange = document.getElementById('renderRange');
        var options = calendar.getOptions();
        var viewName = calendar.getViewName();
        var html = [];
        if (viewName === 'day') {
            html.push(moment(calendar.getDate().getTime()).format('YYYY.MM.DD'));
        } else if (viewName === 'month' &&
            (!options.month.visibleWeeksCount || options.month.visibleWeeksCount > 4)) {
            html.push(moment(calendar.getDate().getTime()).format('YYYY.MM'));
        } else {
            html.push(moment(calendar.getDateRangeStart().getTime()).format('YYYY.MM.DD'));
            html.push(' ~ ');
            html.push(moment(calendar.getDateRangeEnd().getTime()).format(' MM.DD'));
        }
        renderRange.innerHTML = html.join('');
    }

    function setEventListener() {
        //$('.dropdown-menu a[role="menuitem"]').on('click', onClickMenu);
        document.getElementById('menu-navi').addEventListener('click', onClickNavi)
        window.addEventListener('resize', resizeThrottled);
    }

    var resizeThrottled = tui.util.throttle(function () {
        calendar.render();
    }, 50);

    init();

    /*calendar.createSchedules([
        {
            id: '1',
            calendarId: '1',
            title: 'my schedule',
            category: 'time',
            dueDateClass: '',
            start: '2018-01-18T22:30:00+09:00',
            end: '2018-01-19T02:30:00+09:00'
        },
        {
            id: '2',
            calendarId: '1',
            title: 'second schedule',
            category: 'time',
            dueDateClass: '',
            start: '2018-01-18T17:30:00+09:00',
            end: '2018-01-19T17:31:00+09:00',
            isReadOnly: true    // schedule is read-only
        }
    ]);*/

    /*calendar.updateSchedule(schedule.id, schedule.calendarId, {
        start: startTime,
        end: endTime
    });*/

    /*calendar.deleteSchedule(schedule.id, schedule.calendarId);*/

    /*calendar.on('beforeCreateSchedule', function (event) {
        var startTime = event.start;
        var endTime = event.end;
        var isAllDay = event.isAllDay;
        var guide = event.guide;
        var triggerEventName = event.triggerEventName;
        var schedule;

        if (triggerEventName === 'click') {
            // open writing simple schedule popup
            schedule = {...};
        } else if (triggerEventName === 'dblclick') {
            // open writing detail schedule popup
            schedule = {...};
        }

        calendar.createSchedules([schedule]);
    });*/

    /*calendar.on('beforeUpdateSchedule', function(event) {
        var schedule = event.schedule;
        var changes = event.changes;

        calendar.updateSchedule(schedule.id, schedule.calendarId, changes);
    });*/

    /*calendar.on('clickSchedule', function(event) {
        var schedule = event.schedule;

        // focus the schedule
        if (lastClickSchedule) {
            calendar.updateSchedule(lastClickSchedule.id, lastClickSchedule.calendarId, {
                isFocused: false
            });
        }
        calendar.updateSchedule(schedule.id, schedule.calendarId, {
            isFocused: true
        });

        lastClickSchedule = schedule;

        // open detail view
    });*/

    /*Today
    calendar.today();

    Prev
    calendar.prev();

    Next
    calendar.next();*/

    /*// daily view
    calendar.changeView('day', true);

// weekly view
    calendar.changeView('week', true);

// monthly view with 5 weeks or 6 weeks based on the month
    calendar.setOptions({month: {isAlways6Week: false}}, true);
    calendar.changeView('month', true);

// monthly view(default 6 weeks view)
    calendar.setOptions({month: {visibleWeeksCount: 6}}, true); // or null
    calendar.changeView('month', true);

// 2 weeks monthly view
    calendar.setOptions({month: {visibleWeeksCount: 2}}, true);
    calendar.changeView('month', true);

// 3 weeks monthly view
    calendar.setOptions({month: {visibleWeeksCount: 3}}, true);
    calendar.changeView('month', true);

// narrow weekend
    calendar.setOptions({month: {narrowWeekend: true}}, true);
    calendar.setOptions({week: {narrowWeekend: true}}, true);
    calendar.changeView(calendar.getViewName(), true);

// change start day of week(from monday)
    calendar.setOptions({week: {startDayOfWeek: 1}}, true);
    calendar.setOptions({month: {startDayOfWeek: 1}}, true);
    calendar.changeView(calendar.getViewName(), true);

// work week
    calendar.setOptions({week: {workweek: true}}, true);
    calendar.setOptions({month: {workweek: true}}, true);
    calendar.changeView(calendar.getViewName(), true);*/
});

