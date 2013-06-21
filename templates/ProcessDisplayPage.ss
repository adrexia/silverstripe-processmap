<div class="typography">
	<div class="content">
		<div class="process-menu">
			<div class="menu-wrap">
				<a href="./" id="home" title="" class="logo-wrap logo">
					<span class="icon icon-logo"></span>
				</a>
				<a href="#case" class="logo-wrap case-logo">
					<span class="icon icon-case"></span>
				</a>
				<div class="menu-nav-scrollable">
					<nav id="menuList">
						<ol class="num-{$ProcessStages.Count} nav">
							<% loop ProcessStages %>
								<% if $StopStage %>
									<li class="decision-point<% if $First %> active<% end_if %>" data-id="StageID{$ID}">
										<a href="#StageID{$ID}"><span class="icon icon-fork"></span><span class="title">$Title</span></a>
									</li>
									<li class="menu-item item-$Pos stop" data-id="StageID{$StopStage.ID}">
										<a href="#StageID{$StopStage.ID}"><span class="num">$PaddedPos($Pos)</span><span class="title">$StopStage.Title</span></a>
									</li>
								<% else %>
									<li class="menu-item flow item-$Pos<% if $First %> active<% end_if %>" data-id="StageID{$ID}">
										<a href="#StageID{$ID}"><span class="num">$PaddedPos($Pos)</span><span class="title">$Title</span></a>
									</li>
								<% end_if %>
							<% end_loop %>
						</ol>
					</nav>
				</div>
				<div class="controls">
					<div class="buttons">
						<a href="#" class="icon-arrow-up disabled"></a>
						<a href="#" class="icon-arrow-down disabled"></a>
					</div>
				</div>
			</div>
		</div>
		<div class="slides">
			<section class="case step current" id="case">
				<h1 class="step-title">$Title</h1>
				$Content
				<div class="case-wrap">
					<% loop CasesForProcess %>
						<a href="#start" class="process-case item-{$Pos} col_$Modulus(3)" id="Case_{$ID}" data-filter="case_$ID">
							$Title
						</a>
					<% end_loop %>
				</div>
				<button class="page-scroll nav-down icon-arrow-down hidden"></button>
			</section>

			<section class="stages" id="start" data-slide-number="$Process.NumberOfStagesWithStops">
				<% loop ProcessStages %>
					<% if $StopStage %>
						<section class="decision-point step flow $FirstLast" id="StageID{$ID}">
							<button class="page-scroll nav-up icon-arrow-up"></button>

							<h1 class="step-title"><span class="case-name"></span>$Title</h1>
							<button class="choice continue">$ContinueButton</button>
							<button class="choice end">$StopButton</button>
						</section>
						<section class="stop step last" id="StageID{$StopStage.ID}">
							<button class="page-scroll nav-up icon-arrow-up"></button>

							<h1 class="step-title"><span class="case-name"></span>$StopStage.Title</h1>
							<div class="step-items">
							<% loop StopStage.InfoItems %>
								<% include InfoItem %>
							<% end_loop %>
							</div>
						</section>
					<% else %>
						<section class="$FirstLast flow step" id="StageID{$ID}" <% if CaseFinal %>data-finals='[<% loop CaseFinal %>Case_{$ID}<% if not $Last%>, <% end_if %><% end_loop %>]'<% end_if %>>
							<button class="page-scroll nav-up icon-arrow-up"></button>
							<h1 class="step-title"><span class="case-name"></span>$Title</h1>
							<div class="step-items">
								<% loop InfoItems %>
									<% include InfoItem %>
								<% end_loop %>
							</div>
							<div class="stage-content">$Content</div>
							<button class="page-scroll nav-down icon-arrow-down"></button>
						</section>
					<% end_if %>
				<% end_loop %>
			</section>
		</div>
	</div>
</div>