import { apiSlice } from "../api/apiSlice";
import { toastHandler } from "../../../../utils/functions"; // Adjust path based on your structure

export const LogoUploadApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    uploadLogo: builder.mutation({
      query: (formData) => ({
        url: "setting/general-setting/upload-logo",
        method: "POST",
        body: formData, // FormData for file upload
      }),
      async onQueryStarted(arg, { queryFulfilled, dispatch }) {
        try {
          const { data } = await queryFulfilled;
          toastHandler(data.message, "success"); // e.g., "Print Logo uploaded successfully"
        } catch (err) {
          toastHandler("Failed to upload logo, please try again", "warning");
        }
      },
      invalidatesTags: ["setting/generalSetting/uploadLogo"],
    }),
  }),
});

export const { useUploadLogoMutation } = LogoUploadApi;