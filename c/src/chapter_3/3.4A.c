#include <stdio.h>

int main(void)
{
	wchar_t letter = 0;
	
	printf("Enter an uppercase letter:");
	scanf("%lc", &letter);
	
	if (letter >= L'A') {
		if (letter <= L'Z') {
			letter = letter - L'A' + L'a';	
			printf("You entered an uppercase %lc\n", letter);
		} else {
			printf("Try using the shift key,Bud! I want a capital letter.\n");	
		}
	} else {
		printf("You didn't enter an uppercase letter. \n");	
	}

	return 0;
}
