"use client";

import { useState } from "react";
import styles from "./calendar.module.css";

export default function Calendar({ selectedDate, daySelection }) {
  const [currentDate, setCurrentDate] = useState(new Date());

  const daysInMonth = (date) => {
    const year = date.getFullYear();
    const month = date.getMonth();
    return new Date(year, month + 1, 0).getDate();
  };

  const startOfMonth = (date) => {
    return new Date(date.getFullYear(), date.getMonth(), 1);
  };

  const generateCalendar = () => {
    const firstDay = startOfMonth(currentDate).getDay() - 1;
    const totalDays = daysInMonth(currentDate);
    const calendar = [];

    // Fill the array with empty slots for the days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
      calendar.push(null);
    }

    // Fill the array with the days of the month
    for (let i = 1; i <= totalDays; i++) {
      calendar.push(
        new Date(currentDate.getFullYear(), currentDate.getMonth(), i),
      );
    }

    return calendar;
  };

  const nextMonth = () => {
    setCurrentDate(
      new Date(currentDate.getFullYear(), currentDate.getMonth() + 1),
    );
  };

  const prevMonth = () => {
    setCurrentDate(
      new Date(currentDate.getFullYear(), currentDate.getMonth() - 1),
    );
  };

  return (
    <div>
      <div className={styles.monthWrapper}>
        <button onClick={prevMonth}>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth={1.5}
            stroke="currentColor"
            className="w-6 h-6"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M15.75 19.5L8.25 12l7.5-7.5"
            />
          </svg>
        </button>
        <span>
          {currentDate.toLocaleString("default", {
            month: "long",
            year: "numeric",
          })}
        </span>
        <button onClick={nextMonth}>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth={1.5}
            stroke="currentColor"
            className="w-6 h-6"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M8.25 4.5l7.5 7.5-7.5 7.5"
            />
          </svg>
        </button>
      </div>
      <div className={`${styles.calendar_grid}`}>
        <div className={`${styles.calendar_day} ${styles.weekDay}`}>L</div>
        <div className={`${styles.calendar_day} ${styles.weekDay}`}>M</div>
        <div className={`${styles.calendar_day} ${styles.weekDay}`}>X</div>
        <div className={`${styles.calendar_day} ${styles.weekDay}`}>J</div>
        <div className={`${styles.calendar_day} ${styles.weekDay}`}>V</div>
        <div className={`${styles.calendar_day} ${styles.weekDay}`}>S</div>
        <div className={`${styles.calendar_day} ${styles.weekDay}`}>D</div>
        {generateCalendar().map((day, index) => (
          <time
            key={index}
            dateTime={day ? day.toISOString() : ""}
            className={`${styles.calendar_day} ${
              day &&
              day.getTime() === selectedDate?.getTime() &&
              styles.selected_day
            }`}
            onClick={() => daySelection(day)}
          >
            {day ? day.getDate() : ""}
          </time>
        ))}
      </div>
    </div>
  );
}
