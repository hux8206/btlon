@extends('admin.main')
@section('title','User Attempt')
@section('content')
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">
            <i class="fas fa-history mr-2 text-custom-main"></i> Lịch sử người tham gia
        </h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white text-gray-500 text-xs uppercase font-bold border-b">
                <tr>
                    <th class="p-4 pl-6">Người chơi</th>
                    <th class="p-4 text-center">Lần thử</th>
                    <th class="p-4 text-center">Điểm số</th>
                    <th class="p-4 text-right pr-6">Thời gian nộp</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($playerHistory as $h)
                <tr class="hover:bg-blue-50 transition">
                    <td class="p-4 pl-6 font-bold text-gray-700">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-500">
                                <i class="fas fa-user"></i>
                            </div>
                            {{ $h->fullName }}
                        </div>
                    </td>
                    <td class="p-4 text-center text-gray-500">#{{ $h->numOfPlay }}</td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full font-bold {{ $h->correct_question == $h->question_completed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $h->correct_question }} / {{ $h->question_completed }}
                        </span>
                    </td>
                    <td class="p-4 text-right pr-6 text-gray-400">
                        {{ \Carbon\Carbon::parse($h->done_at)->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400 italic">
                        Chưa có ai chơi bài này
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection