"use client";

import { useEffect, useRef, useState } from "react";
import styles from "./colorPicker.module.css";

export default function BgColorPicker({ handleBodyChange }) {
  const [isOpen, setIsOpen] = useState(false);
  const menuRef = useRef(null);

  const colors = [
    {
      nombre: "negro",
      color: "#e5e7eb",
    },
    {
      nombre: "rojo",
      color: "#fecaca",
    },
    {
      nombre: "naranja",
      color: "#fed7aa",
    },
    {
      nombre: "ambar",
      color: "#fde68a",
    },
    {
      nombre: "amarillo",
      color: "#fef08a",
    },
    {
      nombre: "lima",
      color: "#d9f99d",
    },
    {
      nombre: "verde",
      color: "#bbf7d0",
    },
    {
      nombre: "esmeralda",
      color: "#a7f3d0",
    },
    {
      nombre: "turquesa",
      color: "#99f6e4",
    },
    {
      nombre: "cian",
      color: "#a5f3fc",
    },
    {
      nombre: "cielo",
      color: "#bae6fd",
    },
    {
      nombre: "azul",
      color: "#bfdbfe",
    },
    {
      nombre: "indigo",
      color: "#c7d2fe",
    },
    {
      nombre: "violeta",
      color: "#ddd6fe",
    },
    {
      nombre: "morado",
      color: "#e9d5ff",
    },
    {
      nombre: "fuxia",
      color: "#f5d0fe",
    },
    {
      nombre: "rosa",
      color: "#fbcfe8",
    },
    {
      nombre: "rosado",
      color: "#fecdd3",
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
    document.execCommand("hiliteColor", false, color);
    setIsOpen(false);
    handleBodyChange();
  };

  return (
    <div className={styles.wrapper} ref={menuRef}>
      <button onClick={toggleMenu}>
        <img src="/format_color_fill_FILL0_wght300_GRAD0_opsz24.svg" />
      </button>
      {isOpen && (
        <div className={styles.colorPickerMenu}>
          <div
            className={styles.color}
            style={{
              backgroundColor: "#ffffff",
              border: "1px solid rgba(0, 0, 0, .16)",
            }}
            onMouseDown={() => {
              handleColorChange("#ffffff");
            }}
          ></div>
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
