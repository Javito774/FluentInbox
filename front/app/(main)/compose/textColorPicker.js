"use client";

import { useEffect, useRef, useState } from "react";
import styles from "./colorPicker.module.css";

export default function TextColorPicker({ handleBodyChange }) {
  const [isOpen, setIsOpen] = useState(false);
  const menuRef = useRef(null);

  const colors = [
    {
      nombre: "negro",
      color: "#000000",
    },
    {
      nombre: "rojo",
      color: "#ef4444",
    },
    {
      nombre: "naranja",
      color: "#f97316",
    },
    {
      nombre: "ambar",
      color: "#f59e0b",
    },
    {
      nombre: "amarillo",
      color: "#eab308",
    },
    {
      nombre: "lima",
      color: "#84cc16",
    },
    {
      nombre: "verde",
      color: "#22c55e",
    },
    {
      nombre: "esmeralda",
      color: "#10b981",
    },
    {
      nombre: "turquesa",
      color: "#14b8a6",
    },
    {
      nombre: "cian",
      color: "#06b6d4",
    },
    {
      nombre: "cielo",
      color: "#0ea5e9",
    },
    {
      nombre: "azul",
      color: "#3b82f6",
    },
    {
      nombre: "indigo",
      color: "#6366f1",
    },
    {
      nombre: "violeta",
      color: "#8b5cf6",
    },
    {
      nombre: "morado",
      color: "#a855f7",
    },
    {
      nombre: "fuxia",
      color: "#d946ef",
    },
    {
      nombre: "rosa",
      color: "#ec4899",
    },
    {
      nombre: "rosado",
      color: "#f43f5e",
    },
  ];

  useEffect(() => {
    const handleOutsideClick = (event) => {
      if (menuRef.current && !menuRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    };

    document.addEventListener("mousedown", handleOutsideClick);

    return () => {
      document.removeEventListener("mousedown", handleOutsideClick);
    };
  }, []);

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  const handleColorChange = (color) => {
    document.execCommand("foreColor", false, color);
    setIsOpen(false);
    handleBodyChange();
  };

  return (
    <div className={styles.wrapper} ref={menuRef}>
      <button onClick={toggleMenu}>
        <img src="/format_color_text_FILL0_wght300_GRAD0_opsz24.svg" />
      </button>
      {isOpen && (
        <div className={styles.colorPickerMenu}>
          {colors.map((value) => {
            return (
              <div
                className={styles.color}
                key={value.color}
                style={{ backgroundColor: value.color }}
                onMouseDown={() => {
                  handleColorChange(value.color);
                }}
              ></div>
            );
          })}
        </div>
      )}
    </div>
  );
}
