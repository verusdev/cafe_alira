import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import ruLocale from '@fullcalendar/core/locales/ru';
import Chart from 'chart.js/auto';

window.FullCalendar = { Calendar, dayGridPlugin, interactionPlugin, ruLocale };
window.Chart = Chart;
