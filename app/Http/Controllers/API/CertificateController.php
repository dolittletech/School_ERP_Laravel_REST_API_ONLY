<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:student_certificate.view', ['only' => ['index']]);
    //     $this->middleware('permission:student_certificate.add', ['only' => ['create']]);
    //     $this->middleware('permission:student_certificate.edit', ['only' => ['edit']]);
    //     $this->middleware('permission:student_certificate.delete', ['only' => ['delete']]);
    // }

    public function index()
    {
        // if (!auth()->check() || !auth()->user()->can('student_certificate.view')) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        $certificate = new Certificate();
        $certificateList = $certificate->certificateList();
        return response()->json(['certificates' => $certificateList]);
    }

    public function create(Request $request)
    {
        if (!auth()->check() || !auth()->user()->can('student_certificate.add')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'certificate_name' => 'required|string',
            'certificate_text' => 'required|string',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $backgroundImage = '';
        if ($request->hasFile('background_image')) {
            $backgroundImage = $request->file('background_image')->store('certificates', 'public');
        }

        $enableStudentImage = $request->input('is_active_student_img') == 1 ? 1 : 0;
        $imageHeight = $enableStudentImage ? $request->input('image_height', 0) : 0;

        $data = [
            'certificate_name' => $request->input('certificate_name'),
            'certificate_text' => $request->input('certificate_text'),
            'left_header' => $request->input('left_header'),
            'center_header' => $request->input('center_header'),
            'right_header' => $request->input('right_header'),
            'left_footer' => $request->input('left_footer'),
            'right_footer' => $request->input('right_footer'),
            'center_footer' => $request->input('center_footer'),
            'created_for' => 2,
            'status' => 1,
            'background_image' => $backgroundImage,
            'header_height' => $request->input('header_height', 0),
            'content_height' => $request->input('content_height', 0),
            'footer_height' => $request->input('footer_height', 0),
            'content_width' => $request->input('content_width', 0),
            'enable_student_image' => $enableStudentImage,
            'enable_image_height' => $imageHeight,
        ];

        $certificate = new Certificate();
        $result = $certificate->addCertificate($data);

        if ($result) {
            return redirect()->route('admin.certificate.index')
                             ->with('success', __('Success message'));
        }

        return response()->json(['error' => 'Failed to create certificate'], 500);
    }

    public function edit(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->can('student_certificate.edit')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'certificate_name' => 'required|string',
            'certificate_text' => 'required|string',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $enableStudentImage = $request->input('is_active_student_img') == 1 ? 1 : 0;
        $imageHeight = $enableStudentImage ? $request->input('image_height', 0) : 0;

        $data = [
            'id' => $id,
            'certificate_name' => $request->input('certificate_name'),
            'certificate_text' => $request->input('certificate_text'),
            'left_header' => $request->input('left_header'),
            'center_header' => $request->input('center_header'),
            'right_header' => $request->input('right_header'),
            'left_footer' => $request->input('left_footer'),
            'right_footer' => $request->input('right_footer'),
            'center_footer' => $request->input('center_footer'),
            'header_height' => $request->input('header_height', 0),
            'content_height' => $request->input('content_height', 0),
            'footer_height' => $request->input('footer_height', 0),
            'content_width' => $request->input('content_width', 0),
            'enable_student_image' => $enableStudentImage,
            'enable_image_height' => $imageHeight,
        ];

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('certificates', 'public');
        }

        $certificate = new Certificate();
        $result = $certificate->addCertificate($data);

        if ($result) {
            return redirect()->route('admin.certificate.index')
                             ->with('success', __('Update successful'));
        }

        return response()->json(['error' => 'Failed to update certificate'], 500);
    }

    public function delete($id)
    {
        if (!auth()->check() || !auth()->user()->can('student_certificate.delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $certificate = new Certificate();
        $result = $certificate->remove($id);

        if ($result) {
            return redirect()->route('admin.certificate.index')
                             ->with('success', __('Delete successful'));
        }

        return response()->json(['error' => 'Failed to delete certificate'], 500);
    }

    public function view(Request $request)
    {
        $id = $request->input('certificateid');
        $certificate = Certificate::certificateById($id);

        if (!$certificate) {
            return response()->json(['error' => 'Certificate not found'], 404);
        }

        return response()->json(['certificate' => $certificate]);
    }

    public function view1(Request $request)
    {
        $id = $request->input('certificateid');
        $certificate = Certificate::certificateById($id);

        if (!$certificate) {
            return response()->json(['error' => 'Certificate not found'], 404);
        }

        $html = '<div class="tc-container">';
        $html .= '<img src="' . Storage::url($certificate->background_image) . '" width="100%" height="100%" />';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr style="position:absolute; margin-left: auto; margin-right: auto; left: 0; right: 0; width:' . $certificate->content_width . 'px; top:' . $certificate->enable_image_height . 'px">';
        $html .= '<td valign="top" style="position: absolute; right: 0;">';
        if ($certificate->enable_student_image) {
            $html .= '<img src="' . asset('uploads/certificate/noimage.jpg') . '" width="100" height="auto">';
        }
        $html .= '</td></tr>';
        $html .= '<tr style="position:absolute; margin-left: auto; margin-right: auto; left: 0; right: 0; width:' . $certificate->content_width . 'px; top:' . $certificate->header_height . 'px">';
        $html .= '<td style="width:' . $certificate->content_width . 'px; font-size: 18px; text-align:left; position:relative;">' . $certificate->left_header . '</td>';
        $html .= '<td style="width:' . $certificate->content_width . 'px; font-size: 18px; text-align:center; position:relative;">' . $certificate->center_header . '</td>';
        $html .= '<td style="width:' . $certificate->content_width . 'px; font-size: 18px; text-align:right; position:relative;">' . $certificate->right_header . '</td>';
        $html .= '</tr>';
        $html .= '<tr style="position:absolute; margin-left: auto; margin-right: auto; left: 0; right: 0; width:' . $certificate->content_width . 'px; top:' . $certificate->content_height . 'px">';
        $html .= '<td colspan="3" style="font-size: 16px; text-align:center; position:relative;">' . $certificate->certificate_text . '</td>';
        $html .= '</tr>';
        $html .= '<tr style="position:absolute; margin-left: auto; margin-right: auto; left: 0; right: 0; width:' . $certificate->content_width . 'px; top:' . $certificate->footer_height . 'px">';
        $html .= '<td style="width:' . $certificate->content_width . 'px; font-size: 18px; text-align:left;">' . $certificate->left_footer . '</td>';
        $html .= '<td style="width:' . $certificate->content_width . 'px; font-size: 18px; text-align:center;">' . $certificate->center_footer . '</td>';
        $html .= '<td style="width:' . $certificate->content_width . 'px; font-size: 18px; text-align:right;">' . $certificate->right_footer . '</td>';
        $html .= '</tr>';
        $html .= '</table></div>';

        return response()->json(['html' => $html]);
    }
}