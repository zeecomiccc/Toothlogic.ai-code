<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class PostInstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('post_instructions')->delete();

        \DB::table('post_instructions')->insert(array(
            array(
                'id' => 1,
                'title' => 'Tooth Extraction',
                'procedure_type' => 'tooth_extraction',
                'post_instructions' => '<h2 dir="ltr">Tooth Extraction</h2>
                <h3 dir="ltr" style="color:#00C2CB">Do:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Bite gently on the gauze pad for 30–45 minutes to control bleeding.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Apply an ice pack on the outside of your cheek for 10–15 minutes per hour (first 24 hours).</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Eat soft, cool foods (e.g., yogurt, soup, mashed potatoes).</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Keep your head elevated while resting.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Take prescribed medications as directed.</h4>
                </li>
                </ul>
                <h3 dir="ltr" style="color:#00C2CB">Don\'t:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Don\'t rinse your mouth or spit forcefully for 24 hours.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid using a straw or smoking (may dislodge the clot and cause dry socket).</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Don\'t touch the site with fingers or tongue.</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'id' => 2,
                'title' => 'Dental Fillings',
                'procedure_type' => 'dental_fillings',
                'post_instructions' => '<h2 dir="ltr">Dental Fillings</h2>
                <h3 dir="ltr" style="color:#00C2CB">Do:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid chewing on the filled tooth until numbness wears off.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Be cautious of temperature sensitivity for a few days.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Resume normal brushing and flossing.</h4>
                </li>
                </ul>
                <h3 dir="ltr" style="color:#00C2CB">Don\'t:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Don\'t consume very hot or cold foods if sensitivity is present.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid hard or sticky foods for 24 hours (especially with silver/amalgam fillings).</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'id' => 3,
                'title' => 'Root Canal Treatment',
                'procedure_type' => 'root_canal_treatment',
                'post_instructions' => '<h2 dir="ltr">Root Canal Treatment</h2>
                <h3 dir="ltr" style="color:#00C2CB">Do:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Take prescribed antibiotics/painkillers as advised.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Use a soft diet for 1–2 days.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Maintain oral hygiene but be gentle around the treated tooth.</h4>
                </li>
                </ul>
                <h3 dir="ltr" style="color:#00C2CB">Don\'t:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid chewing hard foods on the treated tooth until a crown is placed.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Don\'t miss your follow-up visit to complete the final restoration.</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'id' => 4,
                'title' => 'Crowns and Bridges',
                'procedure_type' => 'crowns_bridges',
                'post_instructions' => '<h2 dir="ltr">Crowns and Bridges</h2>
                <h3 dir="ltr" style="color:#00C2CB">Do:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">If a temporary crown/bridge is placed, chew on the opposite side.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Brush and floss gently around the restoration (especially temporary).</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Contact your dentist if the crown comes off.</h4>
                </li>
                </ul>
                <h3 dir="ltr" style="color:#00C2CB">Don\'t:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid sticky or hard foods around temporary crowns.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Don\'t delay final cementation if only a temporary was placed.</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'id' => 5,
                'title' => 'Dental Implants',
                'procedure_type' => 'dental_implants',
                'post_instructions' => '<h2 dir="ltr">Dental Implants</h2>
                <h3 dir="ltr" style="color:#00C2CB">Do:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Apply ice to reduce swelling for 24 hours.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Take medications as prescribed.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Eat soft food for several days and stay hydrated.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Maintain excellent oral hygiene (rinse with prescribed mouthwash or warm saltwater).</h4>
                </li>
                </ul>
                <h3 dir="ltr" style="color:#00C2CB">Don\'t:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid touching the implant site.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">No smoking or alcohol for at least 72 hours.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Do not skip follow-up appointments.</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'id' => 6,
                'title' => 'Scaling and Root Planing (Deep Cleaning)',
                'procedure_type' => 'scaling_root_planing',
                'post_instructions' => '<h2 dir="ltr">Scaling and Root Planing (Deep Cleaning)</h2>
                <h3 dir="ltr" style="color:#00C2CB">Do:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Use a soft-bristled toothbrush and rinse with chlorhexidine or warm salt water.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Expect slight bleeding or sensitivity for a day or two.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Take prescribed painkillers if discomfort occurs.</h4>
                </li>
                </ul>
                <h3 dir="ltr" style="color:#00C2CB">Don\'t:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid very hot, cold, or spicy food immediately after the procedure.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Don\'t smoke for at least 48 hours.</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'id' => 7,
                'title' => 'Braces (Fixed Orthodontic Treatment)',
                'procedure_type' => 'braces',
                'post_instructions' => '<h2 dir="ltr">Braces (Fixed Orthodontic Treatment)</h2>
                <h3 dir="ltr" style="color:#00C2CB">Do:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Eat soft foods for the first few days.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Use orthodontic wax to cover any brackets causing irritation.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Brush thoroughly and use interdental brushes around brackets.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Wear elastics or appliances as instructed.</h4>
                </li>
                </ul>
                <h3 dir="ltr" style="color:#00C2CB">Don\'t:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid sticky, chewy, and hard foods (gum, popcorn, nuts, candies).</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Don\'t skip follow-up visits or adjustments.</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'id' => 8,
                'title' => 'Clear Aligners',
                'procedure_type' => 'clear_aligners',
                'post_instructions' => '<h2 dir="ltr">Clear Aligners</h2>
                <h3 dir="ltr" style="color:#00C2CB">Do:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Wear aligners for 20–22 hours per day.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Remove aligners while eating and drinking (except water).</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Clean aligners with a soft brush and rinse daily.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Store aligners in the provided case when not in use.</h4>
                </li>
                </ul>
                <h3 dir="ltr" style="color:#00C2CB">Don\'t:</h3>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Do not eat with aligners on.</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Avoid using hot water to clean aligners (can deform them).</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Don\'t skip switching to the next set as instructed.</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'id' => 9,
                'title' => 'When to Contact Your Dentist',
                'procedure_type' => 'contact_dentist',
                'post_instructions' => '<h2 dir="ltr">When to Contact Your Dentist</h2>
                <ul>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Persistent or worsening pain or swelling</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Bleeding that doesn\'t stop after a few hours</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">Fever or signs of infection</h4>
                </li>
                <li dir="ltr" aria-level="1">
                <h4 dir="ltr" role="presentation">A broken or dislodged appliance or restoration</h4>
                </li>
                </ul>',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
    }
}
