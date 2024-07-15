import { getCookie } from "../cookies";

export function getToken() {
  const token = getCookie("sessionToken");
  if (!token) {
    console.log("No hay token");
    window.location = "/login";
  }
  return token;
}

export async function API(path, options) {
  let url = "http://localhost:8080" + path;

  return new Promise(async (resolve, reject) => {
    try {
      const config = {
        headers: {
          Authorization: `Bearer ${getToken()}`,
          "Content-Type": "application/json",
        },
        cache: "no-store",
        ...options,
      };

      const respuesta = await fetch(url, config);

      if (respuesta.ok) {
        const datos = await respuesta.json();
        resolve(datos);
      } else {
        throw new Error(`Error en la petición: ${respuesta.statusText}`);
      }
    } catch (error) {
      reject(new Error(`Error al realizar la petición: ${error.message}`));
    }
  });
}
