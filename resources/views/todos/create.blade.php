@extends('layouts.app')

@section('title', '새 할일 추가 - TodoList')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>새 할일 추가
                </h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('todos.store') }}">
                    @csrf

                    <!-- 제목 입력 -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">
                            <i class="fas fa-pencil-alt text-primary me-2"></i>할일 제목 *
                        </label>
                        <input type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="무엇을 해야 하나요?"
                            required>
                        @error('title')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            명확하고 실행 가능한 제목을 작성해주세요.
                        </div>
                    </div>

                    <!-- 상세 설명 입력 -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-2"></i>상세 설명
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="할일에 대한 자세한 설명을 입력하세요... (선택사항)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            나중에 참고할 수 있도록 세부사항을 적어보세요.
                        </div>
                    </div>

                    <!-- 미리보기 영역 -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-eye text-primary me-2"></i>미리보기
                        </label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="todo-preview">
                                    <div class="d-flex align-items-start">
                                        <div class="form-check me-3 mt-1">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="preview-title text-muted mb-1">할일 제목이 여기에 표시됩니다</h6>
                                            <p class="preview-description text-muted small mb-0">상세 설명이 여기에 표시됩니다</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 버튼 그룹 -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary me-md-2">
                            <i class="fas fa-arrow-left me-2"></i>취소
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>할일 저장
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 도움말 카드 -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>할일 작성 팁
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">
                            <i class="fas fa-check me-2"></i>좋은 예시
                        </h6>
                        <ul class="list-unstyled text-muted small">
                            <li><i class="fas fa-star me-2 text-warning"></i>"Laravel TodoList 프로젝트 완성하기"</li>
                            <li><i class="fas fa-star me-2 text-warning"></i>"오늘 오후 3시까지 보고서 제출"</li>
                            <li><i class="fas fa-star me-2 text-warning"></i>"주말에 운동 30분하기"</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger">
                            <i class="fas fa-times me-2"></i>피해야 할 예시
                        </h6>
                        <ul class="list-unstyled text-muted small">
                            <li><i class="fas fa-minus me-2 text-muted"></i>"공부하기" (너무 모호함)</li>
                            <li><i class="fas fa-minus me-2 text-muted"></i>"정리" (구체적이지 않음)</li>
                            <li><i class="fas fa-minus me-2 text-muted"></i>"나중에 할 것들" (실행하기 어려움)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 실시간 미리보기 기능
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const previewTitle = document.querySelector('.preview-title');
        const previewDescription = document.querySelector('.preview-description');

        // 제목 실시간 업데이트
        titleInput.addEventListener('input', function() {
            const title = this.value.trim();
            if (title) {
                previewTitle.textContent = title;
                previewTitle.classList.remove('text-muted');
                previewTitle.classList.add('text-dark');
            } else {
                previewTitle.textContent = '할일 제목이 여기에 표시됩니다';
                previewTitle.classList.remove('text-dark');
                previewTitle.classList.add('text-muted');
            }
        });

        // 설명 실시간 업데이트
        descriptionInput.addEventListener('input', function() {
            const description = this.value.trim();
            if (description) {
                previewDescription.textContent = description;
                previewDescription.classList.remove('text-muted');
                previewDescription.classList.add('text-secondary');
                previewDescription.style.display = 'block';
            } else {
                previewDescription.textContent = '상세 설명이 여기에 표시됩니다';
                previewDescription.classList.remove('text-secondary');
                previewDescription.classList.add('text-muted');
                previewDescription.style.display = 'block';
            }
        });

        // 폼 제출시 확인
        document.querySelector('form').addEventListener('submit', function(e) {
            const title = titleInput.value.trim();
            if (!title) {
                e.preventDefault();
                titleInput.focus();
                titleInput.classList.add('is-invalid');

                // 임시 에러 메시지 표시
                let errorDiv = titleInput.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    titleInput.parentNode.appendChild(errorDiv);
                }
                errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>할일 제목을 입력해주세요.';
            }
        });

        // 입력시 에러 상태 제거
        titleInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // 키보드 단축키
    document.addEventListener('keydown', function(e) {
        // Ctrl + Enter로 폼 제출
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            document.querySelector('form').submit();
        }

        // Escape로 취소
        if (e.key === 'Escape') {
            if (confirm('작성중인 내용이 있습니다. 정말 나가시겠습니까?')) {
                window.location.href = '{{ route("todos.index") }}';
            }
        }
    });

    // 페이지 이탈시 확인 (내용이 있을 때만)
    window.addEventListener('beforeunload', function(e) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();

        if (title || description) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>
@endsection
