import styles from "./timePicker.module.css";
export default function TimePicker({
  selectedDate,
  selectedHour,
  setSelectedHour,
}) {
  let currentDate = null;

  if (selectedDate) {
    currentDate = new Date(selectedDate);
  } else {
    currentDate = new Date();
  }

  currentDate.setHours(0, 0, 0, 0);

  const availableHours = [];

  const endOfDay = new Date(currentDate);
  endOfDay.setHours(23, 30, 0, 0);

  while (currentDate <= endOfDay) {
    availableHours.push(new Date(currentDate));
    currentDate.setTime(currentDate.getTime() + 1000 * 60 * 30);
  }
  return (
    <div>
      {availableHours.map((hour, index) => (
        <time
          className={`${styles.hour} ${
            hour &&
            hour.getTime() === selectedHour?.getTime() &&
            styles.selected_day
          }`}
          key={index}
          dateTime={hour ? hour.toISOString() : ""}
          onClick={() => {
            setSelectedHour(hour);
          }}
        >
          {hour.toLocaleTimeString("es-ES", {
            hour12: false,
            hour: "2-digit",
            minute: "2-digit",
          })}
        </time>
      ))}
    </div>
  );
}
