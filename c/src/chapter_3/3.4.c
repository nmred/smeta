#include <stdio.h>

int main(void)
{
	char letter = 0;
	
	printf("Enter an uppercase letter:");
	scanf("%c", &letter);
	
	if (letter >= 'A') {
		if (letter <= 'Z') {
			letter = letter - 'A' + 'a';
			printf("You entered an uppercase %c\n", letter);
		}
	} else {
		printf("Try using the shift key,Bud! I want a capital letter.\n");	
	}

	return 0;
}
