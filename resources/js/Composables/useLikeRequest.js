import axiosClient from "@/axiosClient.js";

export default async function useLikeRequest(id, model = "Post") {
    return axiosClient
        .post(route("reactions.react", id), {
            type: "like",
            model: model,
        })
        .catch((error) => {
            console.log(error);
        });
}
