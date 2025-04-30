@extends('website-layouts.app')

@section('title', 'Careers')

@section('content')
<style>
    .careers p, li{
        font-size: 20px;
    }
    table {
        width: 80%;
        margin: 20px 20px;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 20px;
    }
    th,
    td {
        padding: 10px 12px;
        text-align: left;
        border: 1px solid #ddd;
    }
    th {
        background-color: #f9f9f9;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    caption {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    @media screen and (max-width: 768px) {
        .careers p, li{
            font-size: 14px;
        }
        .careers h2,h3{
            font-size: 17px;
        }
        .careers h4{
            font-size: 15px;
        }
        table {
        font-size: 14px;
        width: 90%;
        }
        th,
        td {
        padding: 6px 8px;
        }
        caption {
        font-size: 14px;
        }
    }

    @media screen and (min-width: 1200px) {
        th,
        td {
        padding: 14px 16px;
        }
        caption {
        font-size: 20px;
        }
    }
</style>
<div class="container careers">
    <h1 class="text-center">Careers</h1>
    <h2>Career Opportunities at Swarna Metals Zambia Limited (Kitwe, Copperbelt)</h2>
    <h4>Publication Date: 27th April, 2025.</h4>
    <p>
        Swarna Metals Zambia Limited, a PLR Projects Group Company, is a new and ambitious hydrometallurgical copper extraction plant based in Kitwe, Zambia. We are actively recruiting skilled and experienced professionals to join our team as we prepare to commence production. If you’re ready to contribute your expertise in copper processing to a fast-growing organization in the heart of the Copperbelt, we want to hear from you!
    </p>

    <h2>Available Positions</h2>
    <table>
        <thead>
            <tr>
                <th>S. No.</th>
                <th>Designation</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>1</td><td>Crusher Operators</td></tr>
            <tr><td>2</td><td>Milling and Flotation Operators</td></tr>
            <tr><td>3</td><td>Metallurgists-Flotation</td></tr>
            <tr><td>4</td><td>Leaching, Solvent Extraction and Electrowinning Operators/Attendees</td></tr>
            <tr><td>5</td><td>Leaching, Solvent Extraction and Electrowinning Incharge</td></tr>
            <tr><td>6</td><td>Plasticians/Plastic Welders</td></tr>
            <tr><td>7</td><td>Mechanical Fitters</td></tr>
            <tr><td>8</td><td>Coded Welders</td></tr>
            <tr><td>9</td><td>Electricians</td></tr>
            <tr><td>10</td><td>HR Assistant Officer</td></tr>
            <tr><td>11</td><td>Safety Officer</td></tr>
            <tr><td>12</td><td>Chemist</td></tr>
            <tr><td>13</td><td>Accounts Assistant</td></tr>
            <tr><td>14</td><td>Senior Accountant</td></tr>
            <tr><td>15</td><td>Stores Assistant</td></tr>
        </tbody>
    </table>

    <h2>Open Positions (Job Descriptions & Qualifications)</h2>

    <div>
        <h3>1. Crusher Operators</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Operate crushing equipment and ensure efficient ore size reduction for downstream processing.</li>
            <li>Monitor equipment performance and manage production rates.</li>
            <li>Ensure safe operations in a copper extraction environment.</li>
            <li>Implement regular inspections and maintenance of crushing equipment to maintain operational efficiency.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Craft Certificate or Diploma in Mechanical Engineering, Mining Operations, or a related field.</li>
            <li>Minimum of 3 years experience in ore crushing operations within a mining setting.</li>
            <li>Strong understanding of crushing machinery and safety protocols; copper processing knowledge is a plus.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>2. Milling and Flotation Operators</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Operate milling and flotation equipment.</li>
            <li>Monitor slurry conditions and optimise recovery rates.</li>
            <li>Ensure safe and efficient operations.</li>
            <li>Perform basic maintenance on mills and flotation cells to support continuous production.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Craft Certificate or Diploma in Metallurgy, Mineral Processing, or Mechanical Engineering.</li>
            <li>At least 3 years experience in milling and flotation operations in the mining industry.</li>
            <li>Understanding of flotation chemistry and mill equipment; copper processing experience preferred.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>3. Metallurgists-Flotation</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Oversee milling and flotation operations and supervise the respective operators.</li>
            <li>Monitor slurry conditions, optimise recovery rates, and ensure safe and efficient processes.</li>
            <li>Ensure compliance with industry standards while enhancing efficiency and product quality.</li>
            <li>Member of the Engineering Institute of Zambia (EIZ) with a valid license.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Bachelor’s Degree in Metallurgy, Chemical Engineering, or a related discipline.</li>
            <li>Minimum of 5 years experience in hydrometallurgical copper processing.</li>
            <li>Strong analytical skills and proficiency in process optimisation; copper chemistry knowledge essential.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>4. Leaching, Solvent Extraction and Electrowinning Operators/Attendees</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Manage leaching, solvent extraction (SX), and electrowinning (EW) processes to extract and refine copper.</li>
            <li>Ensure safe handling of chemicals and materials in a hydrometallurgical plant.</li>
            <li>Maintain plant efficiency while following safety and environmental guidelines.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Craft Certificate or Diploma in Metallurgy, Process Engineering, or Chemical Technology.</li>
            <li>3+ years of experience in leaching, SX, or EW operations in a mining setting.</li>
            <li>Familiarity with copper extraction processes and chemical safety protocols.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>5. Leaching, Solvent Extraction and Electrowinning Incharge</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Monitor and supervise leaching, SX, and EW operators/attendees.</li>
            <li>Manage the leaching process, solvent extraction, and EW operations, ensuring high-quality output for downstream processing.</li>
            <li>Monitor and adjust chemical dosages, PH levels, and leach times to achieve optimal copper recovery.</li>
            <li>Ensure safe handling of chemicals and materials in the plant.</li>
            <li>Member of the Engineering Institute of Zambia (EIZ) with a valid license.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Bachelor’s Degree in Metallurgy, Chemical Engineering, or a related discipline.</li>
            <li>At least 5 years of experience in leaching operations within a hydrometallurgical setting.</li>
            <li>Strong knowledge of leaching processes, SX, EW, and copper extraction chemistry.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>6. Plasticians/Plastic Welders</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Perform plastic welding and fabrication to build and maintain HDPE piping and tanks used in copper processing.</li>
            <li>Conduct repairs and perform quality checks to ensure the structural integrity of plastic components.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Craft Certificate or Diploma in Plastic Welding, Fabrication, or a related trade.</li>
            <li>At least 2 years of experience in plastic welding, preferably in mining environments.</li>
            <li>Proficiency in HDPE welding techniques and chemical resistance knowledge.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>7. Mechanical Fitters</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Assemble, install, and maintain mechanical equipment and systems such as pumps and pipes critical to copper processing.</li>
            <li>Conduct inspections and preventive maintenance on machinery.</li>
            <li>Troubleshoot and resolve mechanical issues to minimize downtime.</li>
            <li>Member of the Engineering Institute of Zambia (EIZ) with a valid license.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Craft Certificate or Diploma in Mechanical Engineering or Fitting.</li>
            <li>Minimum of 3 years experience as a mechanical fitter in mining or heavy industry.</li>
            <li>Strong knowledge of mechanical systems, maintenance planning, and problem-solving; copper plant experience is a plus.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>8. Coded Welders</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Execute high-quality welding on metallic structural components and piping used in copper extraction.</li>
            <li>Adhere to coded welding standards to ensure the durability of plant infrastructure.</li>
            <li>Support maintenance and fabrication tasks with expert welding skills.</li>
            <li>Member of the Engineering Institute of Zambia (EIZ) with a valid license.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Craft Certificate or Diploma in Welding and Metal Fabrication.</li>
            <li>3+ years experience in coded welding in mining or heavy industry.</li>
            <li>Expertise in MIG, TIG, and stick welding; familiarity with stainless steel is preferred.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>9. Electricians</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Install, maintain, and repair electrical systems and equipment, including 11 kV HV equipment, HV motors, and PLC automation.</li>
            <li>Conduct fault diagnosis and troubleshooting of electrical systems.</li>
            <li>Perform preventive maintenance to ensure uninterrupted operations.</li>
            <li>Member of the Engineering Institute of Zambia (EIZ) with a valid license.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Craft Certificate or Diploma in Electrical Engineering or Electrical Technology.</li>
            <li>Minimum of 3 years experience in electrical maintenance in mining or industrial settings.</li>
            <li>Strong knowledge of electrical systems in mining and processing plants.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>10. HR Assistant Officer</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Provide comprehensive support in daily HR operations, including recruitment assistance, payroll administration, and record maintenance.</li>
            <li>Ensure adherence to Zambian labor laws and internal HR policies.</li>
            <li>Assist with employee relations, organize training sessions, and facilitate performance evaluations.</li>
            <li>Prepare and manage HR documentation and reports.</li>
            <li>Collaborate with the HR team to align initiatives with overall plant objectives under the guidance of the Plant General Manager and HR Director.</li>
            <li>Women are highly encouraged to apply.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Diploma or Degree in Human Resources, Business Administration, or a related field.</li>
            <li>Minimum of 3 years experience in HR support or assistance, preferably in mining or manufacturing.</li>
            <li>Member of the Zambia Institute Of Human Resource Management (ZIHRM) with a valid license.</li>
            <li>Proficient in Microsoft Office(Word, Excel, PowerPoint ...) and HRMS systems.</li>
            <li>Strong understanding of HR policies, labor laws, and best practices.</li>
            <li>Excellent communication, organizational, and multitasking skills.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>11. Safety Officer</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Ensure a safe working environment at our Kitwe plant by implementing safety policies and conducting risk assessments.</li>
            <li>Train staff on hazard prevention and foster a proactive safety culture.</li>
            <li>Conduct safety audits and prepare safety reports.</li>
            <li>Support the Plant Head in implementing safety initiatives across all operations.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Diploma or Degree in Occupational Health and Safety or a related field.</li>
            <li>Possession of a Zambia Occupational Health and Safety Association (ZOHSA) certificate.</li>
            <li>Minimum of 3 years of safety experience in mining or industrial environments.</li>
            <li>Knowledge of Zambian safety regulations and risk management practices.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>12. Chemist</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Oversee laboratory testing and quality control of ore, process solutions, and copper products.</li>
            <li>Analyse chemical compositions to ensure optimal process performance.</li>
            <li>Maintain laboratory equipment and ensure compliance with industry standards.</li>
            <li>Member of the Engineering Institute of Zambia (EIZ) with a valid license.</li>
            <li>Women are highly encouraged to apply.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Bachelor’s Degree in Chemistry, Metallurgy, or a related field.</li>
            <li>3+ years of experience in laboratory analysis in the mining or metallurgical sector.</li>
            <li>Proficiency in analytical techniques and quality assurance methods.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>13. Accounts Assistant</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Prepare and process NHIMA, VAT Returns, NAPSA, PAYE, and other statutory payments to ensure compliance.</li>
            <li>Assist in the preparation of monthly, quarterly, and annual financial statements and reports.</li>
            <li>Reconcile bank statements, supplier accounts, and other financial records for accuracy.</li>
            <li>Ensure compliance with company accounting policies and financial regulations.</li>
            <li>Assist in payroll processing and ensure correct statutory deductions.</li>
            <li>Provide administrative support to the finance team and assist with accounting tasks.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Diploma or Bachelor's degree in Accounting, Finance,CIMA 2, ACCA 2 or a related field.</li>
            <li>3-5 years of experience in an accounting or finance role.</li>
            <li>Proficiency in NHIMA, VAT Returns, NAPSA, PAYE, and other statutory regulations.</li>
            <li>Strong knowledge of financial reporting, reconciliations, and bookkeeping.</li>
            <li>Ability to work independently and meet tight deadlines.</li>
            <li>Proficiency in Microsoft Office Suite (Excel, Word, PowerPoint).</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>14. Senior Accountant</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Manage and oversee all accounting operations independently for large-scale businesses.</li>
            <li>Maintain accurate financial records using ERP systems and ensure compliance with industry standards.</li>
            <li>Prepare and analyze financial reports, statements, and forecasts for management review.</li>
            <li>Ensure adherence to ISO standards and internal control procedures.</li>
            <li>Collaborate with auditors, tax consultants, and external regulators as required.</li>
            <li>Adapt quickly to new systems and contribute to continuous process improvements.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Bachelor's degree in Accounting, Finance, CIMA 3, ACCA 3, or a related field.</li>
            <li>Minimum of 5 years of experience in an accounting or finance role.</li>
            <li>Proven experience managing accounting functions for large businesses, preferably in the Manufacturing, Infrastructure, or Mining industries.</li>
            <li>Strong working knowledge of ERP systems and hands-on experience with Microsoft Office (Excel, Word, PowerPoint).</li>
            <li>Excellent analytical skills, attention to detail, and the ability to meet deadlines independently.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <div>
        <h3>15. Stores Assistant</h3>
        <p><strong>Job Description:</strong></p>
        <ul>
            <li>Oversee and manage warehouse to ensure timely availability of resources while maintaining compliance with inventory control standards.</li>
        </ul>
        <p><strong>Qualifications Required:</strong></p>
        <ul>
            <li>Diploma or Bachelor's degree in Supply Chain Management or a related field.</li>
            <li>3-5 years of experience in warehouse management.</li>
            <li>Must be an active member of ZIPS (Zambia Institute of Purchasing and Supply).</li>
            <li>Ability to manage heavy equipment and automotive spare parts.</li>
            <li>Ability to work independently and meet tight deadlines.</li>
            <li>Proficiency in Microsoft Office Suite (Excel, Word, PowerPoint).</li>
            <li>Working knowledge in ERP systems is an added advantage.</li>
        </ul>
        <p>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</p>
    </div>

    <h3>General Requirements for All Positions</h3>
    <ul>
        <li>Proven experience in a relevant field.</li>
        <li>Solid leadership and supervisory skills.</li>
        <li>Excellent problem-solving abilities and attention to detail.</li>
        <li>Strong communication and organisational skills.</li>
        <li>Commitment to safety and environmental compliance.</li>
    </ul>

    <p><strong>Application Process</strong></p>
    <p>Please submit your application, including:</p>
    <ul>
        <li>A detailed CV.</li>
        <li>A cover letter.</li>
        <li>Certified copies of academic and professional certificates.</li>
    </ul>

    <p><strong>Submission Options:</strong></p>
    <ul>
        <li class="text-danger"><strong>Note:</strong> Only online applications will be accepted.</li>
        <li>To submit your application, please <a href="{{ route('applications.create') }}">click here</a>.</li>
    </ul>

    <p><strong>Application Deadline:</strong> 17st May 2025</p>
    <p><strong>Why Join Us?</strong> Be part of a dynamic team driving innovation in Zambia’s copper industry. Join us in our journey towards excellence!</p>

</div>
@endsection

