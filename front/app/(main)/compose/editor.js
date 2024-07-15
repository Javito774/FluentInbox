"use client";
import { useEffect, useRef, useState } from "react";
import styles from "./editor.module.css";
import TextColorPicker from "./textColorPicker";
import BgColorPicker from "./bgColorPicker";
import ImagePicker from "./imagePicker";
import AddLink from "./addLink";

export default function Editor({
  mailHTML,
  setMailHTML,
  setMailBody,
  loading,
}) {
  const [editando, setEditando] = useState(true);
  const contenidoEditable = useRef(null);

  useEffect(() => {
    if (!loading) {
      if (mailHTML && mailHTML.trim().length > 0)
        contenidoEditable.current.innerHTML = mailHTML;
    }
  }, [loading]);

  const handleAddLink = (url) => {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
      const range = selection.getRangeAt(0);
      document.execCommand("createLink", false, url);
    }
  };

  const handleBoldClick = () => {
    document.execCommand("bold", false, null);
    handleBodyChange();
  };

  const handleItalicClick = () => {
    document.execCommand("italic", false, null);
    handleBodyChange();
  };

  const handleJustifyLeftClick = () => {
    document.execCommand("justifyLeft", false, null);
    handleBodyChange();
  };

  const handleJustifyCenterClick = () => {
    document.execCommand("justifyCenter", false, null);
    handleBodyChange();
  };

  const handleJustifyRightClick = () => {
    document.execCommand("justifyRight", false, null);
    handleBodyChange();
  };

  const handleBodyChange = () => {
    if (
      contenidoEditable &&
      contenidoEditable.current.innerHTML.trim().length > 0
    ) {
      const images = contenidoEditable.current.querySelectorAll("img");
      images.forEach((image) => {
        image.style.maxWidth = "552px";
        image.style.height = "auto";
      });
      setMailHTML(contenidoEditable.current.innerHTML);
      setMailBody(contenidoEditable.current.innerText);
    } else {
      setMailHTML("");
      setMailBody("");
    }
  };

  return (
    <div className={styles.editorWrapper}>
      {editando && (
        <div className={styles.buttonWrapper}>
          <button onClick={handleBoldClick}>
            <img src="/format_bold_FILL1_wght300_GRAD0_opsz24.svg" />
          </button>
          <button onClick={handleItalicClick}>
            <img src="/format_italic_FILL1_wght300_GRAD0_opsz24.svg" />
          </button>
          <button onClick={handleJustifyLeftClick}>
            <img src="/format_align_left_FILL0_wght300_GRAD0_opsz24.svg" />
          </button>
          <button onClick={handleJustifyCenterClick}>
            <img src="/format_align_center_FILL0_wght300_GRAD0_opsz24.svg" />
          </button>
          <button onClick={handleJustifyRightClick}>
            <img src="/format_align_right_FILL0_wght300_GRAD0_opsz24.svg" />
          </button>
          <TextColorPicker handleBodyChange={handleBodyChange} />
          <BgColorPicker handleBodyChange={handleBodyChange} />
          <AddLink handleAddLink={handleAddLink} />
        </div>
      )}
      <table
        cellSpacing="0"
        cellPadding="0"
        border="0"
        align="center"
        width="100%"
        style={{
          maxWidth: 600 + "px",
          width: 100 + "%",
          background: "#ffffff",
        }}
      >
        <tbody>
          <tr>
            <td>
              <div
                ref={contenidoEditable}
                id="editable"
                className={styles.editable}
                contentEditable
                style={{
                  padding: "12px 24px",
                }}
                onInput={handleBodyChange}
                onBlur={handleBodyChange}
              >
                <div>
                  <img
                    width="60"
                    height="42"
                    src="/Logo.svg"
                    class="custom-logo"
                    alt="ASI Madrid"
                    style={{ maxWidth: "552px", height: "auto", width: 80 + 'px' }}
                  />
                </div>
                <div><br/></div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  );
}
