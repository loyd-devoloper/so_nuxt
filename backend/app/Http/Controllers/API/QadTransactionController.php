<?php

namespace App\Http\Controllers\API;

use NumberFormatter;
use Illuminate\Http\Request;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\School\SoApplication;
use Illuminate\Support\Facades\Crypt;

class QadTransactionController extends Controller
{

    public function generate_so($so_id)
    {
        //Get Application Info
        $soApplication = SoApplication::query()->where(["id" => $so_id])->first();
        if (!empty($soApplication)) {
            if (!empty($soApplication->signed_so_path)) {
                // return $this->loadFile(
                //     SoApplication::class,
                //     $columnName = "signed_so_path",
                //     $id = $so_id,
                // );
            } else {
                // $soApplication->load([
                //     "school",
                //     "checker",
                //     "evaluator",
                //     "signatory",
                //     "reviewer",
                //     "students" => function ($query) {
                //         $query->select("id", "first_name", "last_name", "middle_name", "so_application_id", "so_number")
                //             ->where("status", StudentStatusEnum::Approved->value)
                //             ->whereNotNull("so_number");
                //     }
                // ]);
                $page = 1; // You can change this dynamically
                $generateSO = true;
                $pdf = new Fpdi();
                $pdf->SetTitle($soApplication->students->first()->so_number . "-" . $soApplication->students->last()->so_number);
                $pdf->SetMargins(0, 0, 0, 0);
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                $pdf->SetAutoPageBreak(false);
                  $pdf->setSourceFile(public_path('template/so_template.pdf'));

                while ($generateSO) { //Call the Creating PDF while the generateSO is true
                    $students = $soApplication->students->forPage($page, 30);
                    if ($students->count() > 0) {
                        //Call the function that will generate SO for every 30 student per page
                        $this->prepare_so($pdf, $soApplication, $students, $page);
                        $page++; //After This iteration, add 1 page to check if theres student in the next page
                    } else {
                        $generateSO = false;
                    }
                }
                $pdfContent = $pdf->Output("SO" . $soApplication->id . "NO" . $soApplication->students->first()->so_number . "-" . $soApplication->students->last()->so_number . ".pdf", "S");
                return response($pdfContent, 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Access-Control-Expose-Headers', 'Content-Disposition');
            }
        } else {
            return response()->json(["message" => "S.O not found"]);
        }
    }
 private function prepare_so($pdf, $soApplication, $students, $page)
    {
        $pdf->setSourceFile(public_path('template/so_template.pdf'));
        $tplId = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tplId);
        $pdf->AddPage('P', array($size['width'], $size['height']));
        $pdf->useTemplate($tplId, null, null, $size['width'], $size['height'], false);

        //Header
        //ADD QRCode
        // $pdf->write2DBarcode(route("qr_code.so", ["q" => Crypt::encryptString($soApplication->id)]), 'QRCODE,M', 175, 15, 20, 20, $this->QRCodeStyle(), 'N');
        $pdf->setX(170);
        $pdf->SetFont('BOOKOS', '', 6);
        $pdf->Cell(30, 3, "4A-QAD-SO" . $soApplication->id . "-P." . $page, 0, 1, 'C');

        //Headings
        // Date
        $pdf->SetFont('BOOKOS', '', 11);
        $pdf->setY(50);
        $pdf->setX(12);
        $date = Carbon::parse($soApplication->date_granted)->format('d F Y');
        $pdf->Cell(0, 10, $date, 0, 1);

        // Label (Special Order)
        $pdf->setY(60);
        $pdf->setX(12);
        $pdf->SetFont('BOOKOSB', '', 11);
        $pdf->Cell(0, 10, "SPECIAL ORDER (A)", 0, 1);
        // SO NUMBER AND SERIES
        $pdf->setY(68);
        $pdf->setX(12);
        $pdf->SetFont('BOOKOS', '', 10);
        $year = Carbon::parse($soApplication->date_granted)->format('Y');
        $strings = "No.<b>" . $students->first()->so_number . "-" . $students->last()->so_number . "</b>, s.<b>" . $year . "</b>";
        $this->writeHTML($pdf, $strings);
        $pdf->setY(75);
        //First Stanza
        //Firs Paragraph
        $pdf->SetFont('BOOKOS', '', 10);
        $firstLine = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . "On the basis of the records submitted by <u><b>&nbsp;" . strtoupper($soApplication->school_name) . "</b>, " . $soApplication->school_address . "</u>";

        //Second Paragraph
        $secondLine = "approval of the eligibility for graduation of <b>Senior High School</b>";

        //SPECIALIZATION:
        $specialization = "";
      if ($soApplication->applied_specialization) {
    $specializations = json_decode($soApplication->applied_specialization, true);
    if (is_array($specializations)) {
        $specialization = ''; // Initialize the variable
        $lastItem = end($specializations); // get last item
        foreach ($specializations as $item) {
            $specialization .= $item;
            if ($item !== $lastItem) {
                $specialization .= ", "; // Add comma only if not the last index of array
            }
        }
    }
}

        //Third paragraph
        $thirdLine = "<b>Track :</b> <u><i>" . $soApplication->applied_track . " </i></u> <b>Strand </b>: <u><i>" . $soApplication->applied_strand . "</i></u> ";
        if ($specialization) {
            $thirdLine = $thirdLine . "<b>Specialization : </b><u><i>" . $specialization . "</i></u>";
        }

        //Fourth Paragraph
        $pdf->setX(12);
        $fourthline = "of the following as of <u><b>" . Carbon::parse($soApplication->graduation_date)->format('F Y') . "</b></u> upon the successful completion of the work now being taken in the <b><u>K to 12 Basic Education Program </u></b> is hereby give and made a matter of record.";
        $string = $firstLine . " " . $secondLine . " " . $thirdLine . " " . $fourthline;
        $this->writeHTML($pdf, $string);

        //Second Stanza (List of Students)
        $pdf->Ln(6);
        $pdf->SetFont('BOOKOSB', '', 11);
        $totalStudents = count($students);
        $lineHeight = 6;
        $rows = 1;
        $addedSpace = 0;
        // Determine vertical centering Y offset if students <= 15
        if ($totalStudents <= 15) {
            $totalHeight = $totalStudents * $lineHeight;
            $pageHeight = $pdf->getPageHeight();
            $topMargin = $pdf->getMargins()['top'];
            $bottomMargin = $pdf->getMargins()['bottom'];
            $availableHeight = $pageHeight - $topMargin - $bottomMargin;
            $startY = $topMargin + (($availableHeight - $totalHeight) / 2);
            $pdf->setY($startY);
        } else {
            $yCoordinate = $pdf->getY();
        }

        foreach ($students as $student) {
            $displayFormat = $year . "-" . $student->so_number . ". " . strtoupper($student->last_name) . ", " . ucwords($student->first_name) . " " . ($student->middle_name ? strtoupper(substr($student->middle_name, 0, 1)) : '') . ".";
            if ($totalStudents <= 15) {
                $pdf->setX(60);
                $pdf->Cell(90, $lineHeight, $displayFormat, 0, 1, 'C');
            } else {
                if ($rows < 16) {
                    $pdf->setX(12);
                    $pdf->Cell(90, $lineHeight, $displayFormat, 0, 1, 'L');
                } else {
                    $pdf->setY($yCoordinate + $addedSpace);
                    $pdf->setX(107);
                    $pdf->Cell(90, $lineHeight, $displayFormat, 0, 1, 'L');
                    $addedSpace += $lineHeight;
                }
            }
            $rows++;
        }
        if($totalStudents > 15){ //the label bellow this condition will be fixed its location based on the number of students
            $pdf->setY(194);
        }
        $pdf->SetFont('BOOKOS', '', 10);
        $pdf->Cell(0, 3, "- " . $students->count() . " -", 0, 1, 'C');
        $numberToText = new NumberFormatter('en_US', NumberFormatter::SPELLOUT);
        $pdf->Cell(0, 3, "Valid for " . $numberToText->format($students->count()) . " (" . $students->count() . ") students only.", 0, 1, 'C');

        // Third Stanza
        $string = "The foregoing approval is valid only for  <b>" . Carbon::parse($soApplication->graduation_date)->format('F Y') . ".</b> The approval for any candidate for graduation of the Senior High School is automatically cancelled if he does not complete the full requirements of the course on the date specified, and is subject to revocation if the records upon which the approval is based are later found not correct.";
        $pdf->setY(215);
        $pdf->setX(12);
        $this->writeHTML($pdf, $string);
        if (
            true
        ) { // Only for QAD and admin
            $pdf->setY(235);
            //Last Part (Signatories)
            $pdf->ln(5);
            $pdf->setX(12);
            $pdf->SetFont('BOOKOSB', '', 10);
            $pdf->Cell(0, 3, "(NOT VALID WITHOUT SEAL OR WITH", 0, 1, 'L');
            $pdf->setX(12);
            $pdf->Cell(0, 3, "ERASURE OR ALTERATION)", 0, 1, 'L');

            $pdf->ln(4);
            $pdf->setX(12);
            $pdf->SetFont('BOOKOS', '', 9);
            $pdf->Cell(56, 4, "Enrollment or Form 9 checked by :", 0, 0, 'L');
            $pdf->Cell(45, 4, ucwords($soApplication->checker?->first_name . " " . $soApplication->checker?->last_name), "B", 1, 'L');
            $pdf->Image(storage_path("app/private/" . $soApplication->checker?->esign_path), 99, $pdf->getY() - 5, 10, 10, '', '', '', '', false, 300);

            $pdf->setX(12);
            $pdf->Cell(56, 4, "Verified and Evaluated by :", 0, 0, 'L');
            $pdf->Cell(45, 4, ucwords($soApplication->evaluator?->first_name . " " . $soApplication->evaluator?->last_name), "B", 1, 'L');
            $pdf->Image(storage_path("app/private/" . $soApplication->evaluator?->esign_path), 99, $pdf->getY() - 5, 10, 10, '', '', '', '', false, 300);

            $pdf->setX(12);
            $pdf->Cell(56, 4, "Checked and Reviewed by :", 0, 0, 'L');
            $pdf->Cell(45, 4, ucwords($soApplication->reviewer?->first_name . " " . $soApplication->reviewer?->last_name), "B", 1, 'L');
            $pdf->Image(storage_path("app/private/" . $soApplication->reviewer?->esign_path), 99, $pdf->getY() - 5, 10, 10, '', '', '', '', false, 300);

            //Approving Authorities
            $pdf->setY(250);
            $pdf->setX(130);
            $pdf->SetFont('BOOKOSB', '', 11);
            $pdf->Cell(60, 3, strtoupper($soApplication->signatory?->name), 0, 1, 'C');
            $pdf->SetFont('BOOKOS', '', 10);
            $pdf->setX(130);
            $pdf->Cell(60, 3, ucwords($soApplication->signatory?->position), 0, 0, 'C');
            $pdf->Image(storage_path("app/private/" . $soApplication->approver?->esign_path), 175, $pdf->getY() - 6, 10, 10, '', '', '', '', false, 300);
        } else {
            //Add watermark if school user
            $pdf->Image(public_path("template/watermark_so.png"), 0, 0, $size['width'], $size['height'], '', '', '', false, 300);
        }
    }
private function writeHTML($pdf, $string)
    {
        $pdf->writeHTMLCell(
            185,     // width
            0,       // height (auto)
            '',
            '',  // x, y (empty = current position)
            $string, //  HTML string
            0,       // border
            1,       // ln (1 = move to next line after)
            false,   // fill
            true,    // reset height
            'J'      // alignment
        );
    }
    public function index(Request $request)
    {
        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $applications = SoApplication::query()
            ->with(['curriculumInfo', 'schoolInfo' => function ($query) {
                $query->select('school_number', 'school_name', 'school_address', 'sdo_id')
                    ->with('sdoInformation', function ($query) {
                        $query->select('sdo_name', 'id');
                    });
            }])
            ->withCount('students')
            ->where(function ($query) use ($search) {
                $query->where('applied_strand', 'like', "%{$search}%")
                    ->orWhere('applied_track', 'like', "%{$search}%")
                    ->orWhere('applied_specialization', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('curriculumInfo', function ($query) use ($search) {
                        $query->where('school_year_start', 'like', '%' . $search . '%');
                        $query->where('school_year_end', 'like', '%' . $search . '%');
                    })

                ;
            })
            ->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($applications, 200);
    }
}
