import api from "@/lib/api";

export async function getBarbers(){
    return api.get("/api/v1/usuarios/barberos");
}