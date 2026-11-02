@if ($charts->isNotEmpty())
    <section class="rounded-3xl bg-[#F5F8F5] p-6 sm:p-8" aria-labelledby="annual-financial-trends">
        <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Published annual figures') }}</p>
        <h2 id="annual-financial-trends" class="mt-2 text-2xl font-extrabold text-[#182A22]">{{ __('Yearly financial performance') }}</h2>
        <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __('Compare figures entered and approved by administrators for each published fiscal year.') }}</p>
        <div class="mt-6 grid gap-5 lg:grid-cols-2">
            @foreach ($charts as $metricKey => $statistics)
                @php($maximum = max(0, (float) $statistics->max('value')))
                <section class="rounded-2xl border border-[#DCE9DF] bg-white p-5" aria-labelledby="annual-chart-{{ $metricKey }}">
                    <h3 id="annual-chart-{{ $metricKey }}" class="font-bold text-[#14532D]">{{ $statistics->first()->label() }}</h3>
                    <div role="img" aria-label="{{ __('Fiscal year comparison for :metric', ['metric' => $statistics->first()->label()]) }}" class="mt-5 space-y-4">
                        @foreach ($statistics as $statistic)
                            @php($barWidth = $maximum > 0 ? min(100, ((float) $statistic->value / $maximum) * 100) : 0)
                            <div class="grid grid-cols-[5.5rem_minmax(0,1fr)] items-center gap-3">
                                <span class="text-xs font-semibold text-[#617067]">{{ $statistic->annualReport->report->fiscalYear->localizedLabel() }}</span>
                                <div>
                                    <div class="h-3 overflow-hidden rounded-full bg-[#E7F4EB]">
                                        <div class="h-full rounded-full bg-[#27804A]" style="width: {{ $barWidth }}%"></div>
                                    </div>
                                    <p class="mt-1 text-xs font-semibold text-[#344054]">{{ $statistic->formattedValue() }} {{ $statistic->unit() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>
@endif
