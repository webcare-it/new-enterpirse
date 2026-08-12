<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .json-block {
            white-space: pre-wrap;
            word-break: break-word;
            font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
            font-size: 0.8rem;
            background: #0f172a;
            color: #e2e8f0;
            padding: 1rem 1.2rem;
            border-radius: 0.75rem;
            border: 1px solid #334155;
            margin: 0;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.3);
            line-height: 1.5;
        }

        .json-block .key {
            color: #fcd34d;
        }

        .json-block .string {
            color: #6ee7b7;
        }

        .json-block .number {
            color: #93c5fd;
        }

        .json-block .bracket {
            color: #c084fc;
        }

        .table-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px -12px rgba(0, 0, 0, 0.15), 0 4px 18px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(2px);
            transition: all 0.2s ease;
        }

        .gradient-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-bottom: 2px solid #334155;
        }

        .field-cell {
            background: #f8fafc;
            font-weight: 600;
            color: #0f172a;
            letter-spacing: 0.01em;
            border-right: 1px solid #e9edf2;
        }

        .value-cell {
            background: white;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge-pill {
            background: #eef2ff;
            color: #4338ca;
            font-weight: 500;
            padding: 0.15rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            border: 1px solid #c7d2fe;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .status-badge.inactive {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .status-badge.variant {
            background: #e0f2fe;
            color: #1e40af;
            border: 1px solid #7dd3fc;
        }

        .url-truncate {
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            background: #f1f5f9;
            padding: 0.2rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-family: 'SF Mono', 'Consolas', monospace;
            color: #1e293b;
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 640px) {
            .json-block {
                font-size: 0.7rem;
                padding: 0.75rem;
            }

            .url-truncate {
                white-space: normal;
                word-break: break-all;
            }

            .table-card {
                border-radius: 1rem;
            }
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-slate-100 via-slate-50 to-indigo-50/40 font-sans antialiased p-4 md:p-8 flex items-start justify-center min-h-screen">

    <div class="w-full max-w-6xl mx-auto">
        <!-- page header with stats -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-white/70 backdrop-blur-sm p-2.5 rounded-2xl shadow-sm border border-white/50">
                    <i class="fas fa-cube text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        Product Data
                        <span
                            class="text-xs font-medium bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full border border-indigo-200 ml-2">
                            <i class="far fa-file-alt mr-1"></i> 38 fields
                        </span>
                    </h1>
                </div>
            </div>
            <div
                class="flex items-center gap-2 text-xs bg-white/60 backdrop-blur-sm px-4 py-2 rounded-full shadow-sm border border-white/50">
                <i class="fas fa-sync-alt text-indigo-400 text-xs"></i>
                <span class="text-slate-600">last update: today</span>
            </div>
        </div>

        <!-- main table card -->
        <div class="table-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="gradient-header text-white">
                            <th
                                class="px-6 py-4 text-left font-semibold text-xs uppercase tracking-wider w-[200px] md:w-[240px]">
                                <i class="fas fa-tag mr-2 text-indigo-300"></i> Field
                            </th>
                            <th class="px-6 py-4 text-left font-semibold text-xs uppercase tracking-wider">
                                <i class="fas fa-chevron-circle-right mr-2 text-indigo-300"></i> Value
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        <!-- row helper: each row uses field-cell + value-cell -->
                        <tr>
                            <td class="field-cell px-6 py-3.5">name</td>
                            <td class="value-cell px-6 py-3.5 text-slate-800 font-medium">Data One</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">slug</td>
                            <td class="value-cell px-6 py-3.5 text-slate-400 italic"><span
                                    class="text-slate-300">—</span> (empty)</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">category_id</td>
                            <td class="value-cell px-6 py-3.5 text-slate-800"><span
                                    class="bg-slate-200/70 px-2.5 py-0.5 rounded-full text-xs font-mono">4</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">subcategory_id</td>
                            <td class="value-cell px-6 py-3.5 text-slate-800"><span
                                    class="bg-slate-200/70 px-2.5 py-0.5 rounded-full text-xs font-mono">15</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">brand_id</td>
                            <td class="value-cell px-6 py-3.5 text-slate-800"><span
                                    class="bg-slate-200/70 px-2.5 py-0.5 rounded-full text-xs font-mono">18</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">regular_price</td>
                            <td class="value-cell px-6 py-3.5 text-slate-800 font-medium">$100.00</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">purchase_price</td>
                            <td class="value-cell px-6 py-3.5 text-slate-800 font-medium">$670.00</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">discount</td>
                            <td class="value-cell px-6 py-3.5"><span
                                    class="bg-rose-100 text-rose-700 px-2.5 py-0.5 rounded-full text-xs font-semibold border border-rose-200">56%</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">discount_type</td>
                            <td class="value-cell px-6 py-3.5"><span class="badge-pill">percent</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">stock</td>
                            <td class="value-cell px-6 py-3.5"><span
                                    class="bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full text-xs font-medium border border-emerald-200">100
                                    in stock</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">sku</td>
                            <td
                                class="value-cell px-6 py-3.5 font-mono text-indigo-700 bg-indigo-50/60 px-2 py-0.5 rounded inline-block">
                                SKU001123</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">barcode</td>
                            <td class="value-cell px-6 py-3.5 font-mono text-slate-600">123456789</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">unit</td>
                            <td class="value-cell px-6 py-3.5"><span
                                    class="bg-slate-100 px-2.5 py-0.5 rounded-full text-xs font-medium text-slate-700">Pc</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">low_stock_qty</td>
                            <td class="value-cell px-6 py-3.5"><span
                                    class="bg-amber-50 text-amber-700 px-2.5 py-0.5 rounded-full text-xs border border-amber-200">5</span>
                            </td>
                        </tr>

                        <!-- thumbnail_img -->
                        <tr>
                            <td class="field-cell px-6 py-3.5">thumbnail_img</td>
                            <td class="value-cell px-6 py-3.5">
                                <span class="url-truncate max-w-[280px] inline-block"
                                    title="https://assets.goal.com/images/v3/blt52d5867d0f812c38/GOAL%20-%20Blank%20WEB%20-%20Facebook(1820).jpeg?auto=webp&format=pjpg&width=3840&quality=60">
                                    <i class="fas fa-image mr-1 text-indigo-400"></i> goal.com/thumbnail
                                </span>
                                <span
                                    class="text-xs text-slate-400 block mt-1 truncate max-w-[300px]">https://assets.goal.com/.../Facebook(1820).jpeg</span>
                            </td>
                        </tr>

                        <!-- photos JSON -->
                        <tr>
                            <td class="field-cell px-6 py-3.5 align-top">photos</td>
                            <td class="value-cell px-6 py-3.5">
                                <pre class="json-block">{
  <span class="key">"photos"</span>: [
    <span class="string">"https://assets.goal.com/.../Social-16x9.png"</span>,
    <span class="string">"https://mbpschool.com/.../shutterstock_1256100517-scaled.jpg"</span>,
    <span class="string">"https://hips.hearstapps.com/.../messi-1686171671.jpg"</span>
  ]
}</pre>
                            </td>
                        </tr>

                        <!-- tags -->
                        <tr>
                            <td class="field-cell px-6 py-3.5">tags</td>
                            <td class="value-cell px-6 py-3.5">
                                <span class="inline-flex gap-1.5 flex-wrap">
                                    <span
                                        class="bg-indigo-100 text-indigo-700 text-xs px-3 py-0.5 rounded-full border border-indigo-200"><i
                                            class="fas fa-tag mr-1"></i>new</span>
                                    <span
                                        class="bg-indigo-100 text-indigo-700 text-xs px-3 py-0.5 rounded-full border border-indigo-200"><i
                                            class="fas fa-fire mr-1"></i>popular</span>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="field-cell px-6 py-3.5">short_description</td>
                            <td class="value-cell px-6 py-3.5 text-slate-700 italic">“Short description”</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">description</td>
                            <td class="value-cell px-6 py-3.5 text-slate-700">Full description</td>
                        </tr>

                        <!-- statuses -->
                        <tr>
                            <td class="field-cell px-6 py-3.5">status</td>
                            <td class="value-cell px-6 py-3.5"><span class="status-badge active"><i
                                        class="fas fa-check-circle"></i> 1 · active</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">is_published</td>
                            <td class="value-cell px-6 py-3.5"><span class="status-badge active"><i
                                        class="fas fa-check-circle"></i> 1</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">is_featured</td>
                            <td class="value-cell px-6 py-3.5"><span class="status-badge active"><i
                                        class="fas fa-star text-amber-400"></i> 1</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">best_selling</td>
                            <td class="value-cell px-6 py-3.5"><span class="status-badge inactive"><i
                                        class="fas fa-times-circle"></i> 0</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">is_new_arrival</td>
                            <td class="value-cell px-6 py-3.5"><span class="status-badge inactive"><i
                                        class="fas fa-times-circle"></i> 0</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">todays_deal</td>
                            <td class="value-cell px-6 py-3.5"><span class="status-badge inactive"><i
                                        class="fas fa-times-circle"></i> 0</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">is_variant</td>
                            <td class="value-cell px-6 py-3.5"><span class="status-badge variant"><i
                                        class="fas fa-code-branch"></i> 1 · enabled</span></td>
                        </tr>

                        <tr>
                            <td class="field-cell px-6 py-3.5">video_link</td>
                            <td class="value-cell px-6 py-3.5">
                                <a href="#"
                                    class="text-indigo-600 hover:text-indigo-800 hover:underline transition flex items-center gap-2 bg-indigo-50/60 px-3 py-1 rounded-full border border-indigo-100 w-fit text-xs">
                                    <i class="fab fa-youtube text-red-500 text-base"></i> watch?v=xyz
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">badge_name</td>
                            <td class="value-cell px-6 py-3.5"><span
                                    class="bg-rose-100 text-rose-700 px-3 py-0.5 rounded-full text-xs font-semibold border border-rose-200"><i
                                        class="fas fa-fire"></i> Hot</span></td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">batch_no</td>
                            <td class="value-cell px-6 py-3.5"><span
                                    class="bg-slate-100 px-2.5 py-0.5 rounded-full text-xs">1</span></td>
                        </tr>

                        <tr>
                            <td class="field-cell px-6 py-3.5">shipping_type</td>
                            <td class="value-cell px-6 py-3.5"><span
                                    class="bg-blue-50 text-blue-700 px-2.5 py-0.5 rounded-full text-xs border border-blue-200">flat_rate</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">shipping_cost</td>
                            <td class="value-cell px-6 py-3.5 font-medium">$10.00</td>
                        </tr>

                        <tr>
                            <td class="field-cell px-6 py-3.5">weight</td>
                            <td class="value-cell px-6 py-3.5">0.5 kg</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">length</td>
                            <td class="value-cell px-6 py-3.5">10 cm</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">width</td>
                            <td class="value-cell px-6 py-3.5">5 cm</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">height</td>
                            <td class="value-cell px-6 py-3.5">5 cm</td>
                        </tr>

                        <tr>
                            <td class="field-cell px-6 py-3.5">meta_title</td>
                            <td class="value-cell px-6 py-3.5 font-medium text-slate-700">Sample Meta Title</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">meta_description</td>
                            <td class="value-cell px-6 py-3.5 text-slate-600 italic">“Sample meta description”</td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">meta_img</td>
                            <td class="value-cell px-6 py-3.5">
                                <span class="url-truncate max-w-[200px]"
                                    title="https://a57.foxsports.com/statics.foxsports.com/www.foxsports.com/content/uploads/2025/05/1294/728/messi1.jpg?ve=1&tl=1">
                                    <i class="fas fa-image mr-1 text-indigo-400"></i> foxsports.com/messi
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-cell px-6 py-3.5">position</td>
                            <td class="value-cell px-6 py-3.5"><span
                                    class="bg-slate-100 px-2.5 py-0.5 rounded-full text-xs font-mono">190</span></td>
                        </tr>

                        <!-- variant_attributes JSON -->
                        <tr>
                            <td class="field-cell px-6 py-3.5 align-top">variant_attributes</td>
                            <td class="value-cell px-6 py-3.5">
                                <pre class="json-block">{
  <span class="key">"variant_attributes"</span>: [
    {
      <span class="key">"attributes"</span>: <span class="string">"3/4 Age"</span>,
      <span class="key">"price"</span>: <span class="string">"500"</span>,
      <span class="key">"sku"</span>: <span class="string">"TES585-12A"</span>,
      <span class="key">"quantity"</span>: <span class="string">"250"</span>,
      <span class="key">"image"</span>: <span class="string">"14"</span>
    },
    {
      <span class="key">"attributes"</span>: <span class="string">"4/5 Age"</span>,
      <span class="key">"price"</span>: <span class="string">"500"</span>,
      <span class="key">"sku"</span>: <span class="string">"TES775-34A"</span>,
      <span class="key">"quantity"</span>: <span class="string">"250"</span>,
      <span class="key">"image"</span>: <span class="string">"9"</span>
    },
    {
      <span class="key">"attributes"</span>: <span class="string">"5/6 Age"</span>,
      <span class="key">"price"</span>: <span class="string">"500"</span>,
      <span class="key">"sku"</span>: <span class="string">"TES148-56A"</span>,
      <span class="key">"quantity"</span>: <span class="string">"250"</span>,
      <span class="key">"image"</span>: <span class="string">"5"</span>
    }
  ]
}</pre>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- subtle footer -->
            <div
                class="bg-slate-50/70 backdrop-blur-sm px-6 py-3 text-xs text-slate-400 border-t border-slate-200/80 flex flex-wrap justify-between items-center gap-2">
                <span><i class="far fa-clock mr-1 text-indigo-300"></i> Data snapshot · all fields</span>
                <span class="flex items-center gap-2">
                    <i class="fas fa-circle text-emerald-400 text-[6px]"></i>
                    <span>38 rows · variant enabled</span>
                </span>
            </div>
        </div>
    </div>

</body>

</html>
